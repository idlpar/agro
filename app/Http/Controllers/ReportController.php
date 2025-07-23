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
            ->whereNull('deleted_at') // Exclude soft-deleted transactions
            ->get();

        // Fetch payments
        $payments = Payment::with(['customer', 'receiver'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->whereNull('deleted_at') // Exclude soft-deleted payments
            ->get();

        // Fetch new customers
        $customers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Calculate customer financial metrics
        $customerFinancials = User::where('role', 'customer')
            ->select('users.id', 'users.name')
            ->selectSub(function ($query) use ($startDate, $endDate) {
                $query->selectRaw('COALESCE(SUM(total_amount), 0)')
                    ->from('transactions')
                    ->whereColumn('user_id', 'users.id')
                    ->whereNull('deleted_at') // Exclude soft-deleted transactions
                    ->whereBetween('transaction_date', [$startDate, $endDate]);
            }, 'total_revenue')
            ->selectSub(function ($query) use ($startDate, $endDate) {
                $query->selectRaw('COALESCE(SUM(amount), 0)')
                    ->from('payments')
                    ->whereColumn('user_id', 'users.id')
                    ->whereNull('deleted_at') // Exclude soft-deleted payments
                    ->whereBetween('payment_date', [$startDate, $endDate]);
            }, 'total_paid')
            ->get()
            ->map(function ($customer) {
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