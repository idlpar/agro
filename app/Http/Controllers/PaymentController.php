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
        $totalAllocated = 0;
        foreach ($payments as $payment) {
            $totalAllocated += $payment->transactions->sum('pivot.allocated_amount');
        }
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
                ->with(['dueTransactions.payments']) // Eager load payments for due transactions
                ->orderBy('name')
                ->get()
                ->map(function ($customer) {
                    $customer->calculated_due_amount = $customer->dueTransactions->sum('total_amount') -
                        $customer->dueTransactions->sum(function($t) {
                            return $t->payments->sum('pivot.allocated_amount');
                        });
                    return $customer;
                });
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
        ]);

        DB::transaction(function () use ($payment, $validated) {
            // Find all transactions that were linked to this payment before any changes
            $previouslyLinkedTransactions = $payment->transactions()->get();

            // Soft delete all existing allocations from the pivot table
            if ($previouslyLinkedTransactions->isNotEmpty()) {
                $payment->transactions()->updateExistingPivot($previouslyLinkedTransactions->pluck('id'), ['deleted_at' => now()]);
            }

            // Force each of those previously linked transactions to re-evaluate its paid status
            foreach ($previouslyLinkedTransactions as $transaction) {
                $transaction->updatePaymentStatus();
            }

            // Now, update the payment record itself with the new amount and details
            $payment->update($validated);

            // Always re-allocate the payment's new amount to the customer's due transactions
            $customer = User::find($validated['user_id']);
            if ($customer) {
                $this->allocatePaymentToTransactions($payment, $customer, $validated['amount']);
            }
        });

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully and reallocated.');
    }


    public function trashed()
    {
        $this->authorize('viewAny', Payment::class);

        $payments = Payment::onlyTrashed()->with(['customer', 'receiver'])->latest()->paginate(20);

        return view('payments.trashed', compact('payments'));
    }

    public function restore(Payment $payment)
    {
        $this->authorize('update', $payment);

        DB::transaction(function () use ($payment) {
            // Restore the soft-deleted payment
            $payment->restore();

            // Restore the soft-deleted pivot table entries
            DB::table('payment_transaction')
                ->where('payment_id', $payment->id)
                ->update(['deleted_at' => null]);
        });

        return redirect()->route('payments.trashed')->with('success', 'Payment restored successfully.');
    }

    public function forceDelete(Payment $payment)
    {
        $this->authorize('delete', $payment);

        DB::transaction(function () use ($payment) {
            // Permanently delete the pivot table entries for this payment
            DB::table('payment_transaction')
                ->where('payment_id', $payment->id)
                ->delete();

            // Permanently delete the payment
            $payment->forceDelete();
        });

        return redirect()->route('payments.trashed')->with('success', 'Payment permanently deleted.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        DB::transaction(function () use ($payment) {
            // Find all transactions linked to this payment before we do anything.
            $linkedTransactions = $payment->transactions()->get();

            // First, remove the links from the pivot table.
            $payment->transactions()->detach();

            // Now, delete the payment record itself.
            $payment->delete();

            // Finally, loop through each previously linked transaction and force it to update its status.
            // This ensures that if a transaction was marked as 'paid' only because of this payment,
            // it will be correctly updated to 'due' or 'partial'.
            foreach ($linkedTransactions as $transaction) {
                $transaction->updatePaymentStatus();
            }
        });

        return back()->with('success', 'Payment deleted successfully and transaction statuses have been updated.');
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
        $customersQuery = User::where('role', 'customer')
            ->select(['users.*'])
            ->selectRaw('(
            SELECT COALESCE(SUM(transactions.total_amount), 0)
            FROM transactions
            WHERE transactions.user_id = users.id AND transactions.deleted_at IS NULL
        ) as total_transactions')
            ->selectRaw('(
            SELECT COALESCE(SUM(payments.amount), 0)
            FROM payments
            WHERE payments.user_id = users.id AND payments.deleted_at IS NULL
        ) as total_paid')
            ->selectRaw('(
            SELECT MAX(transactions.transaction_date)
            FROM transactions
            WHERE transactions.user_id = users.id AND transactions.deleted_at IS NULL
        ) as last_transaction_date')
            ->selectRaw('(
            SELECT COUNT(*)
            FROM transactions
            WHERE transactions.user_id = users.id AND transactions.deleted_at IS NULL
        ) as transactions_count')
            ->selectRaw('(
            SELECT COUNT(*)
            FROM transactions
            WHERE transactions.user_id = users.id AND transactions.is_paid = 1 AND transactions.deleted_at IS NULL
        ) as paid_transactions_count')
            ->selectRaw('(
            SELECT COUNT(*)
            FROM transactions
            WHERE transactions.user_id = users.id AND transactions.is_paid = 0 AND transactions.deleted_at IS NULL
        ) as unpaid_transactions_count');

        // Filter customers with due amounts before pagination
        $customers = $customersQuery->get()->filter(function ($customer) {
            return ($customer->total_transactions - $customer->total_paid) > 0;
        });

        // Manually paginate the filtered collection
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $customers->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $customers = new LengthAwarePaginator($currentItems, $customers->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

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
