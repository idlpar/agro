<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Payment::query()
            ->with(['customer', 'receiver', 'transactions'])
            ->when($request->filled('customer_id'), function ($q) use ($request) {
                $q->where('user_id', $request->customer_id);
            })
            ->when($request->filled('receiver_id'), function ($q) use ($request) {
                $q->where('received_by', $request->receiver_id);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'allocated') {
                    $q->has('transactions');
                } elseif ($request->status === 'unallocated') {
                    $q->doesntHave('transactions');
                }
            })
            ->when($request->filled('date_range'), function ($q) use ($request) {
                if ($request->date_range === 'today') {
                    $q->whereDate('payment_date', Carbon::today());
                } elseif ($request->date_range === 'this_week') {
                    $q->whereBetween('payment_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($request->date_range === 'this_month') {
                    $q->whereBetween('payment_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                } elseif ($request->date_range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
                    $q->whereBetween('payment_date', [$request->start_date, $request->end_date]);
                }
            })
            ->when($request->filled('amount_min'), function ($q) use ($request) {
                $q->where('amount', '>=', $request->amount_min);
            })
            ->when($request->filled('amount_max'), function ($q) use ($request) {
                $q->where('amount', '<=', $request->amount_max);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('customer', function ($customerQuery) use ($request) {
                    $customerQuery->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('phone', 'like', '%'.$request->search.'%')
                        ->orWhere('email', 'like', '%'.$request->search.'%');
                });
            });

        // Apply user-specific restrictions
        if ($user->isAdmin()) {
            $payments = $query->latest();
        } elseif ($user->isStaff()) {
            $payments = $query->where('received_by', $user->id)->latest();
        } elseif ($user->isCustomer()) {
            $payments = $user->payments()
                ->with('receiver')
                ->latest();
        }

        // Export handling
        if ($request->has('export')) {
            return $this->exportPayments($query->get());
        }

        $payments = $payments->paginate(20);

        // Get filter options
        $customers = $user->isAdmin()
            ? User::customer()->orderBy('name')->get()
            : ($user->isStaff() ? $user->createdCustomers : []);

        $receivers = $user->isAdmin()
            ? User::staff()->orderBy('name')->get()
            : [];

        // Calculate summary stats
        $totalAmount = $payments->sum('amount');
        $totalAllocated = $payments->sum(function($payment) {
            return $payment->transactions->sum('total_amount');
        });
        $totalUnallocated = $totalAmount - $totalAllocated;

        return view('payments.index', compact(
            'payments',
            'customers',
            'receivers',
            'totalAmount',
            'totalAllocated',
            'totalUnallocated'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        $customers = [];

        if ($user->isAdmin() || $user->isStaff()) {
            $customers = User::customer()
                ->withSum(['transactions as total_due' => function($q) {
                    $q->where('is_paid', false);
                }], 'total_amount')
                ->orderBy('name')
                ->get();
        } else {
            abort(403);
        }

        return view('payments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
            'mark_transactions_paid' => 'sometimes|boolean'
        ]);

        $customer = User::findOrFail($validated['user_id']);
        $validated['received_by'] = auth()->id();

        DB::transaction(function () use ($validated, $request, $customer) {
            $payment = Payment::create($validated);

            if ($request->boolean('mark_transactions_paid')) {
                $this->allocatePaymentToTransactions($payment, $customer, $validated['amount']);
            }
        });

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully');
    }

    protected function allocatePaymentToTransactions(Payment $payment, User $customer, float $paymentAmount): void
    {
        // Get all unpaid or partially paid transactions, ordered by date (oldest first)
        $transactions = $customer->transactions()
            ->withSum('payments as total_paid', 'payment_transaction.allocated_amount')
            ->orderBy('transaction_date')
            ->get();

        $amountToAllocate = $paymentAmount;

        foreach ($transactions as $transaction) {
            if ($amountToAllocate <= 0) break;

            // Calculate remaining due for this transaction
            $remainingDue = $transaction->total_amount - ($transaction->total_paid ?? 0);

            // Skip if already fully paid
            if ($remainingDue <= 0 || $transaction->is_paid) continue;


            // Determine how much to allocate to this transaction
            $amountToApply = min($remainingDue, $amountToAllocate);

            // Create the payment allocation
            $payment->transactions()->attach($transaction->id, [
                'allocated_amount' => $amountToApply
            ]);

            // Update transaction status
            $transaction->refresh();
            $transaction->updatePaymentStatus();

            // Reduce remaining amount to allocate
            $amountToAllocate -= $amountToApply;
        }

        // If there's still money left after allocating to all transactions
        if ($amountToAllocate > 0) {
            // You can either:
            // 1. Create a credit balance for the customer
            // 2. Return the excess amount
            // Here we'll just log it for now
            \Log::info("Excess payment of {$amountToAllocate} not allocated to any transaction");
        }
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['customer', 'receiver', 'transactions.variant.product']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);

        $customers = auth()->user()->isAdmin()
            ? User::customer()->orderBy('name')->get()
            : auth()->user()->createdCustomers;

        $payment->load('transactions');

        return view('payments.edit', compact('payment', 'customers'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
            'mark_transactions_paid' => 'sometimes|boolean'
        ]);

        DB::transaction(function () use ($payment, $validated, $request) {
            // Detach all existing allocations
            $payment->transactions()->detach();

            // Update payment details
            $payment->update($validated);

            if ($request->boolean('mark_transactions_paid')) {
                $customer = User::find($validated['user_id']);
                $this->allocatePaymentToTransactions($payment, $customer, $validated['amount']);
            }
        });

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully');
    }


    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        DB::transaction(function () use ($payment) {
            // Reverse any transaction markings before deleting
            $payment->transactions()->update(['is_paid' => false]);
            $payment->delete();
        });

        return back()->with('success', 'Payment deleted successfully');
    }

    public function customerPayments($customerId)
    {
        $customer = User::findOrFail($customerId);
        $this->authorize('view', $customer);

        $payments = $customer->payments()
            ->with(['receiver', 'transactions.variant.product'])
            ->latest()
            ->paginate(20);

        $dueAmount = $customer->transactions()
            ->unpaid()
            ->sum('total_amount');

        return view('payments.customer-payments', compact('customer', 'payments', 'dueAmount'));
    }

    protected function exportPayments($payments)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments_'.date('Y-m-d').'.csv"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Payment ID',
                'Date',
                'Customer',
                'Amount',
                'Received By',
                'Allocated Amount',
                'Notes'
            ]);

            // Add data rows
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->customer->name,
                    $payment->amount,
                    $payment->receiver->name,
                    $payment->transactions->sum('total_amount'),
                    $payment->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function dueList()
    {
        // Get all customers with their due amounts calculated in a subquery
        $customers = User::where('role', 'customer')
            ->select(['users.*'])
            ->selectRaw('(
            SELECT COALESCE(SUM(transactions.total_amount), 0)
            FROM transactions
            WHERE transactions.user_id = users.id
        ) as total_transactions')
            ->selectRaw('(
            SELECT COALESCE(SUM(payment_transaction.allocated_amount), 0)
            FROM payment_transaction
            JOIN transactions ON payment_transaction.transaction_id = transactions.id
            WHERE transactions.user_id = users.id
        ) as total_paid')
            ->selectRaw('(
            SELECT MAX(transactions.transaction_date)
            FROM transactions
            WHERE transactions.user_id = users.id
        ) as last_transaction_date')
            ->havingRaw('total_transactions > total_paid')
            ->withCount([
                'transactions',
                'transactions as paid_transactions_count' => function($query) {
                    $query->where('is_paid', true);
                },
                'transactions as unpaid_transactions_count' => function($query) {
                    $query->where('is_paid', false);
                }
            ])
            ->paginate(20);

        // Calculate summary statistics
        $totalDueAmount = $customers->sum(function($customer) {
            return $customer->total_transactions - $customer->total_paid;
        });

        $averageDue = $customers->count() > 0 ? $totalDueAmount / $customers->count() : 0;

        // Add total_due to each customer for easier access in the view
        $customers->getCollection()->transform(function($customer) {
            $customer->total_due = $customer->total_transactions - $customer->total_paid;
            return $customer;
        });

        return view('payments.due-list', [
            'customers' => $customers,
            'totalDueAmount' => $totalDueAmount,
            'averageDue' => $averageDue
        ]);
    }

    public function visitList()
    {
        // First get the paginated customers
        $customers = User::where('role', 'customer')
            ->withCount([
                'transactions',
                'transactions as unpaid_transactions_count' => function($query) {
                    $query->where('is_paid', false);
                }
            ])
            ->with(['transactions' => function($query) {
                $query->select('user_id', DB::raw('SUM(total_amount) as total_amount'))
                    ->groupBy('user_id');
            }])
            ->with(['transactions.payments' => function($query) {
                $query->select('transaction_id', DB::raw('SUM(payment_transaction.allocated_amount) as paid_amount'));
            }])
            ->withMax('payments as last_payment_date', 'payment_date')
            ->paginate(20);

        // Calculate accurate due amounts
        $customers->getCollection()->transform(function($customer) {
            $totalAmount = $customer->transactions->sum('total_amount');
            $totalPaid = $customer->transactions->sum(function($transaction) {
                return $transaction->payments->sum('paid_amount');
            });
            $customer->total_due = max($totalAmount - $totalPaid, 0);
            return $customer;
        });

        // Filter out customers with no due (after pagination)
        $customers->setCollection(
            $customers->getCollection()->filter(function($customer) {
                return $customer->total_due > 0;
            })
        );

        // Calculate collection statistics from the filtered collection
        $totalCollectable = $customers->sum(function($customer) {
            return min($customer->total_due, 5000);
        });
        $averagePerVisit = $customers->count() > 0 ? $totalCollectable / $customers->count() : 0;

        return view('payments.visit-list', [
            'customers' => $customers,
            'totalCollectable' => $totalCollectable,
            'averagePerVisit' => $averagePerVisit
        ]);
    }
    public function customerTransactions(User $customer)
    {
        $transactions = $customer->transactions()
            ->with(['variant.product', 'payments'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        return view('payments.customer-transactions', compact('customer', 'transactions'));
    }
}
