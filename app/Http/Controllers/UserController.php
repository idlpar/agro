<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query
        $query = User::query()->with('creator');

        // Apply role-based restrictions
        if ($user->isAdmin()) {
            $query->whereIn('role', ['admin', 'staff', 'customer']);
        } elseif ($user->isStaff()) {
            $query->where('created_by', $user->id)
                ->where('role', 'customer');
        } else {
            abort(403);
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
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->onlyTrashed();
            }
        } else {
            // By default show active users only
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
        }

        $users = $query->latest()->paginate(25);

        return view('users.index', compact('users', 'creators'));
    }

    public function create()
    {
        $user = auth()->user();
        $roles = [];

        if ($user->isAdmin()) {
            $roles = ['staff'];
        } elseif ($user->isStaff()) {
            $roles = ['customer'];
        } else {
            abort(403);
        }

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'regex:/^[0-9]{11}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:staff,customer'], // Adjust as needed
        ]);

        $createdUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'created_by' => $user->id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $roles = User::roles();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'sometimes|string|in:admin,staff,customer',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        if (isset($validated['role'])) {
            $updateData['role'] = $validated['role'];
        }

        if ($user->update($updateData)) {
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        }

        return back()->with('error', 'Failed to update user.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'User restored successfully.');
    }

    public function staffIndex()
    {
        $staff = User::staff()
            ->withCount('createdCustomers')
            ->orderBy('name')
            ->get();

        return view('users.staff-index', [
            'staff' => $staff
        ]);
    }

}
