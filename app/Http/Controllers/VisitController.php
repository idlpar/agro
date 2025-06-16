<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Visit::query()
            ->with(['customer', 'assignedTo'])
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'upcoming') {
                    $q->where('scheduled_date', '>=', now());
                } elseif ($request->status === 'completed') {
                    $q->where('scheduled_date', '<', now())
                        ->where('completed_at', '!=', null);
                } elseif ($request->status === 'missed') {
                    $q->where('scheduled_date', '<', now())
                        ->where('completed_at', null);
                }
            })
            ->when($request->filled('date_range'), function ($q) use ($request) {
                if ($request->date_range === 'today') {
                    $q->whereDate('scheduled_date', Carbon::today());
                } elseif ($request->date_range === 'this_week') {
                    $q->whereBetween('scheduled_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($request->date_range === 'this_month') {
                    $q->whereBetween('scheduled_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                }
            })
            ->when($request->filled('assigned_to'), function ($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });

        // For staff members, only show their own visits
        if (Auth::user()->isStaff()) {
            $query->where('assigned_to', Auth::id());
        }

        $visits = $query->orderBy('scheduled_date')->paginate(20);

        // Get customers with outstanding payments for the create form
        $customers = User::customer()
            ->has('transactions', '>', 0)
            ->orderBy('name')
            ->get();

        // Get staff members for assignment dropdown
        $staffMembers = User::staff()->orderBy('name')->get();

        return view('visits.index', compact('visits', 'customers', 'staffMembers'));
    }

    public function create()
    {
        $customers = User::customer()
            ->withSum(['transactions as total_due' => function($q) {
                $q->where('is_paid', false);
            }], 'total_amount')
            ->having('total_due', '>', 0)
            ->orderBy('name')
            ->get();

        $staffMembers = User::staff()->orderBy('name')->get();

        return view('visits.create', compact('customers', 'staffMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'assigned_to' => 'required|exists:users,id',
            'scheduled_date' => 'required|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'expected_amount' => 'nullable|numeric|min:0'
        ]);

        $visit = Visit::create($validated);

        return redirect()->route('visits.index')->with('success', 'Visit scheduled successfully');
    }

    public function show(Visit $visit)
    {
        $this->authorize('view', $visit);

        $visit->load(['customer', 'assignedTo', 'customer.transactions' => function($q) {
            $q->where('is_paid', false)->orderBy('transaction_date');
        }]);

        return view('visits.show', compact('visit'));
    }

    public function edit(Visit $visit)
    {
        $this->authorize('update', $visit);

        $customers = User::customer()
            ->withSum(['transactions as total_due' => function($q) {
                $q->where('is_paid', false);
            }], 'total_amount')
            ->having('total_due', '>', 0)
            ->orderBy('name')
            ->get();

        $staffMembers = User::staff()->orderBy('name')->get();

        return view('visits.edit', compact('visit', 'customers', 'staffMembers'));
    }

    public function update(Request $request, Visit $visit)
    {
        $this->authorize('update', $visit);

        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'assigned_to' => 'required|exists:users,id',
            'scheduled_date' => 'required|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'expected_amount' => 'nullable|numeric|min:0',
            'completed_at' => 'nullable|date',
            'collected_amount' => 'nullable|numeric|min:0',
            'outcome' => 'nullable|string'
        ]);

        $visit->update($validated);

        return redirect()->route('visits.index')->with('success', 'Visit updated successfully');
    }

    public function destroy(Visit $visit)
    {
        $this->authorize('delete', $visit);

        $visit->delete();

        return redirect()->route('visits.index')->with('success', 'Visit deleted successfully');
    }

    public function markComplete(Visit $visit)
    {
        $this->authorize('markComplete', $visit);

        $visit->update([
            'completed_at' => now(),
            'collected_amount' => request('collected_amount', 0),
            'outcome' => request('outcome', 'Completed')
        ]);

        return back()->with('success', 'Visit marked as completed');
    }

    public function collectionList()
    {
        $this->authorize('viewCollectionList', Visit::class);

        $query = User::customer()
            ->withSum(['transactions as total_due' => function($q) {
                $q->where('is_paid', false);
            }], 'total_amount')
            ->having('total_due', '>', 0)
            ->orderBy('total_due', 'desc');

        // For staff members, only show their assigned customers
        if (Auth::user()->isStaff()) {
            $query->where('created_by', Auth::id());
        }

        $customers = $query->paginate(20);

        // Calculate priority based on due amount and last payment
        $customers->getCollection()->transform(function($customer) {
            $customer->priority_score = $this->calculatePriorityScore($customer);
            return $customer;
        });

        // Sort by priority score
        $customers->setCollection($customers->getCollection()->sortByDesc('priority_score'));

        return view('visits.collection-list', compact('customers'));
    }

    protected function calculatePriorityScore($customer)
    {
        $dueAmount = $customer->total_due;
        $lastPaymentDays = $customer->transactions()
            ->where('is_paid', true)
            ->latest('transaction_date')
            ->value('transaction_date')
            ? Carbon::parse($customer->transactions()
                ->where('is_paid', true)
                ->latest('transaction_date')
                ->value('transaction_date'))
                ->diffInDays(now())
            : 90; // Default to 90 days if no payments

        // Higher score for higher due amounts and longer time since last payment
        return ($dueAmount * 0.7) + ($lastPaymentDays * 0.3);
    }
}
