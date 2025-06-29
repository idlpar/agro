<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Transaction::query()
            ->with(['customer', 'variant.product', 'creator'])
            ->when($request->filled('customer_id'), function ($q) use ($request) {
                $q->where('user_id', $request->customer_id);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('is_paid', $request->status === 'paid');
            })
            ->when($request->filled('product_id'), function ($q) use ($request) {
                $q->whereHas('variant', function ($variantQuery) use ($request) {
                    $variantQuery->where('product_id', $request->product_id);
                });
            })
            ->when($request->filled('date_from') && $request->filled('date_to'), function ($q) use ($request) {
                $q->whereBetween('transaction_date', [
                    $request->date_from,
                    $request->date_to
                ]);
            });

        // Apply user-specific restrictions
        if ($user->isAdmin()) {
            $transactions = $query->latest();
        } elseif ($user->isStaff()) {
            $transactions = $query->where('created_by', $user->id)->latest();
        } elseif ($user->isCustomer()) {
            $transactions = $user->transactions()
                ->with(['variant.product', 'creator'])
                ->latest();
        }

        $transactions = $transactions->paginate(20);

        // Get totals for summary cards
        $totalAmount = $transactions->sum('total_amount');  // Total amount of all transactions
        $partialPayments = $transactions->sum('partial_pay');  // Sum of direct partial payments
        $allocatedPayments = $transactions->sum(function ($transaction) {
            return $transaction->payments()->sum('allocated_amount');
        });  // Sum of allocated payments through payment_transactions

        // Calculate final amount due
        $totalDue = $totalAmount - $allocatedPayments;
        $firstTotalAmount = $totalAmount - $partialPayments;
        // Get filter options
        $customers = $user->isAdmin()
            ? User::customer()->orderBy('name')->get()
            : ($user->isStaff() ? $user->createdCustomers : []);

        $products = ProductVariant::with('product')->get()->pluck('product')->unique();

        return view('transactions.index', compact(
            'transactions',
            'customers',
            'products',
            'firstTotalAmount',
            'totalDue'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        $customers = [];
        $variants = ProductVariant::with('product')->get();

        if ($user->isAdmin() || $user->isStaff()) {
            $customers = User::customer()
                ->with(['dueTransactions', 'allTransactions'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            abort(403);
        }

        return view('transactions.create', compact('customers', 'variants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'transaction_date' => 'required|date',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'is_paid' => 'required|in:0,1,2', // 0=due, 1=paid, 2=partial
            'discount_amount' => 'nullable|numeric|min:0',
            'partial_payment_amount' => 'nullable|required_if:is_paid,2|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $validated['created_by'] = Auth::id();

        DB::beginTransaction();

        try {
            $validated['total_amount'] = ($validated['quantity'] * $validated['unit_price']) - ($validated['discount_amount'] ?? 0);
            $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
            $validated['partial_pay'] = $validated['partial_payment_amount'] ?? 0;
            $validated['is_paid'] = $validated['is_paid'] == 1; // Convert to boolean for database

            $transaction = Transaction::create($validated);

            // Handle payment based on status
            if ($validated['is_paid']) {
                // Full payment
                $payment = Payment::create([
                    'user_id' => $validated['user_id'],
                    'received_by' => auth()->id(),
                    'payment_date' => $validated['transaction_date'],
                    'amount' => $validated['total_amount'],
                    'notes' => 'Instant payment with transaction'
                ]);

                $transaction->payments()->attach($payment->id, [
                    'allocated_amount' => $validated['total_amount']
                ]);
            } elseif ($request->is_paid == 2) {
                // Partial payment
                $payment = Payment::create([
                    'user_id' => $validated['user_id'],
                    'received_by' => auth()->id(),
                    'payment_date' => $validated['transaction_date'],
                    'amount' => $validated['partial_payment_amount'],
                    'notes' => 'Partial payment with transaction'
                ]);

                $transaction->payments()->attach($payment->id, [
                    'allocated_amount' => $validated['partial_payment_amount']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction recorded successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to record transaction: ' . $e->getMessage());
        }
    }



    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load([
            'customer',
            'variant.product',
            'creator',
            'payments' => function($q) {
                $q->orderBy('payment_transaction.created_at', 'desc')
                    ->limit(5);
            }
        ]);

        $relatedTransactions = Transaction::where('user_id', $transaction->user_id)
            ->where('id', '!=', $transaction->id)
            ->with('variant.product')
            ->latest()
            ->limit(5)
            ->get();

        return view('transactions.show', compact('transaction', 'relatedTransactions'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $variants = ProductVariant::with('product')->get();
        $customers = auth()->user()->isAdmin()
            ? User::where('role', 'customer')->get()
            : auth()->user()->createdCustomers;

        $customerDue = $transaction->customer->dueTransactions()
            ->where('id', '!=', $transaction->id)
            ->sum('total_amount');

        return view('transactions.edit', compact(
            'transaction',
            'variants',
            'customers',
            'customerDue'
        ));
    }

//    public function update(Request $request, Transaction $transaction)
//    {
//        $this->authorize('update', $transaction);
//
//        $validated = $request->validate([
//            'product_variant_id' => 'required|exists:product_variants,id',
//            'transaction_date' => 'required|date',
//            'quantity' => 'required|numeric|min:0.01',
//            'unit_price' => 'required|numeric|min:0',
//            'discount_amount' => 'nullable|numeric|min:0',
//            'is_paid' => 'required|in:0,1,2', // 0=due, 1=paid, 2=partial
//            'partial_payment_amount' => 'nullable|required_if:is_paid,2|numeric|min:0',
//            'notes' => 'nullable|string',
//        ]);
//        $validated['created_by'] = Auth::id();
//
//        DB::beginTransaction();
//
//        try {
//            $validated['total_amount'] = ($validated['quantity'] * $validated['unit_price']) - ($validated['discount_amount'] ?? 0);
//            $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
//            $validated['is_paid'] = $validated['is_paid'] == 1; // Convert to boolean for database
//
//            $transaction->update($validated);
//
//            // Get total payments made so far
//            $paidAmount = $transaction->paid_amount;
//            $newStatus = $request->is_paid;
//
//            // Handle payment status changes
//            if ($newStatus == 1 && !$transaction->isFullyPaid()) {
//                // Mark as fully paid - pay remaining amount
//                $remaining = $validated['total_amount'] - $paidAmount;
//
//                $payment = Payment::create([
//                    'user_id' => $transaction->user_id,
//                    'received_by' => auth()->id(),
//                    'payment_date' => $validated['transaction_date'],
//                    'amount' => $remaining,
//                    'notes' => 'Payment recorded with transaction update'
//                ]);
//
//                $transaction->payments()->attach($payment->id, [
//                    'allocated_amount' => $validated['total_amount']
//                ]);
//            } elseif ($newStatus == 2) {
//                // Partial payment - add the partial amount
//                $payment = Payment::create([
//                    'user_id' => $transaction->user_id,
//                    'received_by' => auth()->id(),
//                    'payment_date' => $validated['transaction_date'],
//                    'amount' => $validated['partial_payment_amount'],
//                    'notes' => 'Partial payment recorded with transaction update'
//                ]);
//
//                $transaction->payments()->attach($payment->id, [
//                    'allocated_amount' => $validated['partial_payment_amount']
//                ]);
//            }
//
//            DB::commit();
//
//            return redirect()
//                ->route('transactions.index')
//                ->with('success', 'Transaction updated successfully');
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return back()
//                ->withInput()
//                ->with('error', 'Failed to update transaction: ' . $e->getMessage());
//        }
//    }


    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'transaction_date' => 'required|date',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'is_paid' => 'required|in:0,1,2', // 0=due, 1=paid, 2=partial
            'partial_payment_amount' => 'nullable|required_if:is_paid,2|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Calculate new total amount
            $newTotalAmount = ($validated['quantity'] * $validated['unit_price']) - ($validated['discount_amount'] ?? 0);

            // For transactions with existing payments, lock financial fields
            if ($transaction->hasPayments()) {
                $originalValues = [
                    'quantity' => $transaction->quantity,
                    'unit_price' => $transaction->unit_price,
                    'discount_amount' => $transaction->discount_amount,
                    'total_amount' => $transaction->total_amount,
                ];

                // Merge original financial values to prevent changes
                $validated = array_merge($validated, $originalValues);
            } else {
                // For unpaid transactions, update financial fields normally
                $validated['total_amount'] = $newTotalAmount;
                $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
            }

            // Handle payment status changes regardless of existing payments
            $validated['is_paid'] = $validated['is_paid'] == 1; // Convert to boolean
            $validated['partial_pay'] = $validated['partial_payment_amount'] ?? 0;

            // Update the transaction
            $transaction->update($validated);

            // Get total payments made so far
            $paidAmount = $transaction->paid_amount;
            $newStatus = $request->is_paid;

            // Handle payment status changes
            if ($newStatus == 1 && !$transaction->isFullyPaid()) {
                // Mark as fully paid - pay remaining amount
                $remaining = $validated['total_amount'] - $paidAmount;

                $payment = Payment::create([
                    'user_id' => $transaction->user_id,
                    'received_by' => auth()->id(),
                    'payment_date' => $validated['transaction_date'],
                    'amount' => $remaining,
                    'notes' => 'Payment recorded with transaction update'
                ]);

                $transaction->payments()->attach($payment->id, [
                    'allocated_amount' => $remaining
                ]);
            } elseif ($newStatus == 2) {
                // Partial payment - add the partial amount
                $payment = Payment::create([
                    'user_id' => $transaction->user_id,
                    'received_by' => auth()->id(),
                    'payment_date' => $validated['transaction_date'],
                    'amount' => $validated['partial_payment_amount'],
                    'notes' => 'Partial payment recorded with transaction update'
                ]);

                $transaction->payments()->attach($payment->id, [
                    'allocated_amount' => $validated['partial_payment_amount']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        DB::beginTransaction();

        try {
            $transaction->payments()->delete();
            $transaction->delete();

            DB::commit();

            return back()->with('success', 'Transaction deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $user = auth()->user();
        $customers = $user->isAdmin()
            ? User::where('role', 'customer')->orderBy('name')->get()
            : ($user->isStaff() ? $user->createdCustomers : []);

        $customerId = $request->input('customer_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = collect();
        $payments = collect();
        $customer = null;

        if ($customerId) {
            $customer = User::find($customerId);

            if ($customer) {
                $transactionsQuery = $customer->transactions()
                    ->with(['variant.product', 'creator']);

                $paymentsQuery = $customer->payments()
                    ->with('receiver');

                if ($startDate && $endDate) {
                    $transactionsQuery->whereBetween('transaction_date', [$startDate, $endDate]);
                    $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
                }

                $transactions = $transactionsQuery->get();
                $payments = $paymentsQuery->get();
            }
        }

        return view('transactions.report', compact(
            'customers',
            'customer',
            'transactions',
            'payments',
            'startDate',
            'endDate'
        ));
    }

    public function getCustomerDue($customerId)
    {
        $customer = User::findOrFail($customerId);

        $transactions = $customer->allTransactions()
            ->with('variant.product')
            ->orderBy('transaction_date')
            ->get();

        $totalDue = 0;
        $transactions->each(function ($transaction) use (&$totalDue) {
            if (!$transaction->is_paid) {
                $totalDue += $transaction->total_amount - $transaction->payments()->sum('amount');
            }
        });

        return response()->json([
            'total_due' => $totalDue,
            'transactions' => $transactions->take(10),
            'total_transactions' => $customer->allTransactions->count(),
            'paid_transactions' => $customer->paidTransactions->count(),
            'partial_transactions' => $customer->allTransactions->filter->isPartiallyPaid()->count()
        ]);
    }
}
