<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $user = auth()->user();

        // Base query for customers
        $query = User::query()->with('creator')->where('role', 'customer');

        // Apply role-based restrictions
        if (!$user->isAdmin()) {
            $query->where('created_by', $user->id);
        }

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->onlyTrashed();
            }
        } else {
            // By default show active customers only
            $query->whereNull('deleted_at');
        }

        // Date range filters
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at',
                        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'custom':
                    if ($request->filled('start_date')) {
                        $query->whereDate('created_at', '>=', $request->start_date);
                    }
                    if ($request->filled('end_date')) {
                        $query->whereDate('created_at', '<=', $request->end_date);
                    }
                    break;
            }
        }

        // Get creators list for admin filter
        if ($user->isAdmin()) {
            $creators = User::whereIn('role', ['admin', 'staff'])
                ->select('id', 'name')
                ->get();
        } else {
            $creators = [];
        }

        $customers = $query->latest()->paginate(20);

        return view('customers.index', compact('customers', 'creators'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('customers.create');
    }

    /**
     * Store a newly created in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'regex:/^[0-9]{11}$/', 'unique:users,phone'],
            'address' => ['nullable'],
            'password' => ['nullable', 'min:8'],
        ]);

        // Generate or use provided password
        $rawPassword = $validated['password'] ?? Str::random(10); // optional: send this to user via SMS/email
        $hashedPassword = bcrypt($rawPassword);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => $hashedPassword,
            'role' => 'customer',
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }


    /**
     * Display the customer.
     */
    public function show(User $customer)
    {
        $this->authorize('view', $customer);

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the customer.
     */
    public function edit(User $customer)
    {
        $this->authorize('update', $customer);

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        $this->authorize('update', $customer);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($customer->id)],
            'phone' => ['nullable', 'regex:/^[0-9]{11}$/', Rule::unique('users')->ignore($customer->id)],
            'address' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        if ($customer->update($updateData)) {
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        }

        return back()->with('error', 'Failed to update customer.');
    }

    /**
     * Remove the customer from storage.
     */
    public function destroy(User $customer)
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * Restore a soft-deleted customer.
     */
    public function restore($id)
    {
        $customer = User::withTrashed()->findOrFail($id);

        $this->authorize('delete', $customer); // <- $customer is now defined!

        $customer->restore();

        return redirect()->route('customers.index')->with('success', 'Customer restored successfully.');
    }

}
