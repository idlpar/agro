<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class PaymentTransactionSeeder extends Seeder
{
    public function run()
    {
        // Get all payments and unpaid/partially paid transactions
        $payments = Payment::with('customer')->get();
        $transactions = Transaction::with('customer')
            ->where('is_paid', false)
            ->orWhere(function($query) {
                $query->where('is_paid', false)
                    ->where('partial_pay', '>', 0);
            })
            ->get();

        foreach ($payments as $payment) {
            // Only allocate payments to transactions for the same customer
            $customerTransactions = $transactions->where('user_id', $payment->user_id);

            if ($customerTransactions->isEmpty()) {
                continue;
            }

            $remainingAmount = $payment->amount;
            $allocatedTransactions = [];

            foreach ($customerTransactions as $transaction) {
                if ($remainingAmount <= 0) {
                    break;
                }

                $dueAmount = $transaction->total_amount - $transaction->paid_amount;
                $allocatedAmount = min($dueAmount, $remainingAmount);

                if ($allocatedAmount > 0) {
                    $allocatedTransactions[$transaction->id] = [
                        'allocated_amount' => $allocatedAmount
                    ];
                    $remainingAmount -= $allocatedAmount;
                }
            }

            // Attach the transactions with allocated amounts
            if (!empty($allocatedTransactions)) {
                $payment->transactions()->attach($allocatedTransactions);

                // Update transaction payment statuses
                foreach ($allocatedTransactions as $transactionId => $allocation) {
                    $transaction = Transaction::find($transactionId);
                    $transaction->updatePaymentStatus();
                }
            }
        }

        // Mark some transactions as fully paid
        $transactionsToMarkPaid = Transaction::where('is_paid', false)
            ->has('payments')
            ->get()
            ->filter(function($transaction) {
                return $transaction->paid_amount >= $transaction->total_amount;
            });

        foreach ($transactionsToMarkPaid as $transaction) {
            $transaction->update(['is_paid' => true]);
        }
    }
}
