<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function generate()
    {
        // Get date range from request, default to last month to now
        $startDate = request('start_date', now()->subMonth()->startOfDay());
        $endDate = request('end_date', now()->endOfDay());

        // Validate and parse dates
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Fetch transactions
        $transactions = Transaction::with(['customer', 'variant.product'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        // Fetch payments
        $payments = Payment::with(['customer', 'receiver'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();

        // Fetch new customers
        $customers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Calculate customer financial metrics
        $customerFinancials = User::where('role', 'customer')
            ->select('users.id', 'users.name')
            ->withSum([
                'transactions as total_revenue' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                }
            ], 'total_amount')
            ->get()
            ->map(function ($customer) use ($startDate, $endDate) {
                // Calculate total paid for this customer’s transactions in the date range
                $totalPaid = DB::table('payment_transaction')
                    ->join('transactions', 'payment_transaction.transaction_id', '=', 'transactions.id')
                    ->where('transactions.user_id', $customer->id)
                    ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
                    ->sum('payment_transaction.allocated_amount');

                $customer->total_revenue = $customer->total_revenue ?? 0;
                $customer->total_paid = $totalPaid ?? 0;
                $customer->total_due = $customer->total_revenue - $customer->total_paid;
                return $customer;
            })
            ->filter(function ($customer) {
                // Only include customers with non-zero financial activity
                return $customer->total_revenue > 0 || $customer->total_paid > 0 || $customer->total_due > 0;
            });

        // Prepare chart data
        $chartData = [
            'labels' => $customerFinancials->pluck('name')->toArray(),
            'revenue' => $customerFinancials->pluck('total_revenue')->toArray(),
            'paid' => $customerFinancials->pluck('total_paid')->toArray(),
            'due' => $customerFinancials->pluck('total_due')->toArray(),
        ];

        return view('reports.generate', [
            'transactions' => $transactions,
            'payments' => $payments,
            'customers' => $customers,
            'customerFinancials' => $customerFinancials,
            'chartData' => $chartData,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }
}