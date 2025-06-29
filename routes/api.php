<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/customers/{customer}/summary-data', function (User $customer) {
    // Get all transactions for the customer
    $transactions = $customer->transactions()
        ->with(['payments'])
        ->get();

    // Calculate totals
    $totalAmount = $transactions->sum('total_amount');
    $transactionCount = $transactions->count();

    // Calculate total paid amount (sum of all allocated amounts from payment_transaction)
    $paidAmount = DB::table('payment_transaction')
        ->join('transactions', 'payment_transaction.transaction_id', '=', 'transactions.id')
        ->where('transactions.user_id', $customer->id)
        ->sum('payment_transaction.allocated_amount');

    // Calculate due amount
    $dueAmount = max($totalAmount - $paidAmount, 0);

    // Get recent transactions (last 5)
    $recentTransactions = $customer->transactions()
        ->with(['variant.product', 'payments'])
        ->orderBy('transaction_date', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'transaction_date' => $transaction->transaction_date,
                'product_name' => $transaction->variant->product->name ?? null,
                'variant_name' => $transaction->variant->name ?? null,
                'quantity' => $transaction->quantity,
                'unit_price' => $transaction->unit_price,
                'total_amount' => $transaction->total_amount,
                'is_paid' => $transaction->is_paid,
                'paid_amount' => $transaction->paid_amount,
                'due_amount' => $transaction->due_amount,
            ];
        });

    return response()->json([
        'transaction_count' => $transactionCount,
        'total_amount' => $totalAmount,
        'paid_amount' => $paidAmount,
        'due_amount' => $dueAmount,
        'recent_transactions' => $recentTransactions
    ]);
});
