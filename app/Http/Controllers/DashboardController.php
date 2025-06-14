<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Customer stats
        $totalCustomers = User::customer()->count();
        $lastMonthCustomers = User::customer()
            ->where('created_at', '<', Carbon::now()->subMonth())
            ->count();
        $customerGrowth = $lastMonthCustomers > 0
            ? round(($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers * 100, 2)
            : 100;

        // Staff stats
        $totalStaff = User::staff()->count();

        // Transaction stats
        $monthlyRevenue = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('total_amount');

        $lastMonthRevenue = Transaction::whereMonth('transaction_date', now()->subMonth()->month)
            ->whereYear('transaction_date', now()->subMonth()->year)
            ->sum('total_amount');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round(($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100, 2)
            : 100;

        // Payment stats
        $pendingPayments = Transaction::where('is_paid', false)->sum('total_amount');
        $pendingTransactions = Transaction::where('is_paid', false)->count();

        // Recent transactions
        $recentTransactions = Transaction::with(['customer', 'variant.product'])
            ->latest()
            ->take(5)
            ->get();

        // Recent payments
        $recentPayments = Payment::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Revenue chart data (last 6 months)
        $revenueChart = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueChart['labels'][] = $date->format('M Y');

            $revenue = Transaction::whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('total_amount');

            $revenueChart['data'][] = $revenue;
        }

        // Customer chart data (last 6 months)
        $customerChart = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $customerChart['labels'][] = $date->format('M Y');

            $customers = User::customer()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $customerChart['data'][] = $customers;
        }

        return view('dashboard', compact(
            'totalCustomers',
            'customerGrowth',
            'totalStaff',
            'monthlyRevenue',
            'revenueGrowth',
            'pendingPayments',
            'pendingTransactions',
            'recentTransactions',
            'recentPayments',
            'revenueChart',
            'customerChart'
        ));
    }
}
