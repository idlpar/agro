<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Visit;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->staffDashboard();
    }

    protected function adminDashboard()
    {
        // Customer stats
        $totalCustomers = User::customer()->count();
        $activeCustomers = User::customer()->has('transactions')->count();
        $newCustomers = User::customer()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Staff stats
        $totalStaff = User::staff()->count();
        $activeStaff = User::staff()->has('createdCustomers')->count();

        // Transaction stats
        $totalRevenue = Transaction::sum('total_amount');
        $monthlyRevenue = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('total_amount');

        $revenueGrowth = $this->calculateRevenueGrowth();

        // Outstanding payments
        $outstandingPayments = DB::table('transactions')
            ->select(DB::raw('SUM(total_amount - COALESCE((SELECT SUM(allocated_amount) FROM payment_transaction WHERE payment_transaction.transaction_id = transactions.id), 0)) as outstanding'))
            ->where('is_paid', false)
            ->value('outstanding');

        // Payment stats
        $totalPayments = Payment::sum('amount');
        $monthlyPayments = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Visit stats
        $totalVisits = Visit::count();
        $completedVisits = Visit::whereNotNull('completed_at')->count();
        $upcomingVisitsCount = Visit::where('scheduled_date', '>=', now())
            ->where('scheduled_date', '<=', now()->addDays(7))
            ->count();

        // Product stats
        $totalProducts = Product::count();
        $totalVariants = ProductVariant::count();

        // Top selling variants
        $topSellingVariants = ProductVariant::with('product')
            ->select('product_variants.*')
            ->selectSub(function($query) {
                $query->from('transactions')
                    ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                    ->whereColumn('transactions.product_variant_id', 'product_variants.id');
            }, 'sold_quantity')
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();

        // Recent data
        $recentTransactions = Transaction::with(['customer', 'variant.product'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['customer', 'receiver'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingVisits = Visit::with(['customer', 'assignedTo'])
            ->where('scheduled_date', '>=', now())
            ->where('scheduled_date', '<=', now()->addDays(7))
            ->orderBy('scheduled_date')
            ->get();

        // Due transactions
        $dueTransactions = Transaction::with(['customer', 'variant.product'])
            ->where('is_paid', false)
            ->withSum('payments as paid_amount', 'payment_transaction.allocated_amount')
            ->orderBy('transaction_date')
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                $transaction->due_amount = $transaction->total_amount - $transaction->paid_amount;
                return $transaction;
            });

        // Chart data
        $revenueChartData = $this->getRevenueChartData();
        $customerGrowthChartData = $this->getCustomerGrowthChartData();
        $paymentCollectionChartData = $this->getPaymentCollectionChartData();
        $productPerformanceChartData = $this->getProductPerformanceChartData();

        return view('dashboard.admin', [
            // Customer metrics
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'newCustomers' => $newCustomers,

            // Staff metrics
            'totalStaff' => $totalStaff,
            'activeStaff' => $activeStaff,

            // Transaction metrics
            'totalRevenue' => $totalRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'revenueGrowth' => $revenueGrowth,
            'outstandingPayments' => $outstandingPayments,
            'recentTransactions' => $recentTransactions,

            // Payment metrics
            'totalPayments' => $totalPayments,
            'monthlyPayments' => $monthlyPayments,
            'recentPayments' => $recentPayments,

            // Visit metrics
            'totalVisits' => $totalVisits,
            'completedVisits' => $completedVisits,
            'upcomingVisitsCount' => $upcomingVisitsCount,
            'upcomingVisits' => $upcomingVisits,

            // Product metrics
            'totalProducts' => $totalProducts,
            'totalVariants' => $totalVariants,
            'topSellingVariants' => $topSellingVariants,

            // Due transactions
            'dueTransactions' => $dueTransactions,

            // Chart data
            'revenueChartData' => $revenueChartData,
            'customerGrowthChartData' => $customerGrowthChartData,
            'paymentCollectionChartData' => $paymentCollectionChartData,
            'productPerformanceChartData' => $productPerformanceChartData,
        ]);
    }

    protected function staffDashboard()
    {
        $staffId = auth()->id();

        // Staff-specific customer stats
        $myCustomers = User::customer()
            ->where('created_by', $staffId)
            ->count();

        $myActiveCustomers = User::customer()
            ->where('created_by', $staffId)
            ->has('transactions')
            ->count();

        // Staff revenue stats
        $myMonthlyRevenue = Transaction::whereHas('customer', function($query) use ($staffId) {
            $query->where('created_by', $staffId);
        })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('total_amount');

        // FIXED: Outstanding payments for staff's customers
        $myOutstandingPayments = DB::table('transactions')
            ->select(DB::raw('SUM(total_amount - COALESCE((SELECT SUM(allocated_amount) FROM payment_transaction WHERE payment_transaction.transaction_id = transactions.id), 0)) as outstanding'))
            ->where('is_paid', false)
            ->whereExists(function ($query) use ($staffId) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.id', 'transactions.user_id')
                    ->where('users.created_by', $staffId);
            })
            ->value('outstanding');

        // Staff performance metrics
        $myPerformance = [
            'transactions' => Transaction::whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
                ->where('transaction_date', '>=', now()->subDays(30))
                ->count(),

            'visits_completed' => Visit::where('assigned_to', $staffId)
                ->whereNotNull('completed_at')
                ->where('completed_at', '>=', now()->subDays(30))
                ->count(),
        ];

        // Staff upcoming visits
        $myUpcomingVisits = Visit::with(['customer', 'assignedTo'])
            ->where('assigned_to', $staffId)
            ->where('scheduled_date', '>=', now())
            ->where('scheduled_date', '<=', now()->addDays(7))
            ->orderBy('scheduled_date')
            ->get();

        // Staff recent transactions
        $myRecentTransactions = Transaction::with(['customer', 'variant.product'])
            ->whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
            ->latest()
            ->take(5)
            ->get();

        // Staff recent payments
        $myRecentPayments = Payment::with(['customer', 'receiver'])
            ->whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
            ->latest()
            ->take(5)
            ->get();

        // Staff top products
        $myTopProducts = ProductVariant::with('product')
            ->select('product_variants.*')
            ->selectSub(function($query) use ($staffId) {
                $query->from('transactions')
                    ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                    ->whereColumn('transactions.product_variant_id', 'product_variants.id')
                    ->whereExists(function ($subQuery) use ($staffId) {
                        $subQuery->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('users.id', 'transactions.user_id')
                            ->where('users.created_by', $staffId);
                    });
            }, 'sold_quantity')
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();

        // Customers with due payments (staff's customers only)
        $customersWithDue = User::customer()
            ->where('created_by', $staffId)
            ->withSum(['transactions as total_due' => function($query) {
                $query->select(DB::raw('SUM(total_amount - COALESCE((SELECT SUM(allocated_amount) FROM payment_transaction WHERE payment_transaction.transaction_id = transactions.id), 0))'))
                    ->where('is_paid', false);
            }], 'total_amount')
            ->having('total_due', '>', 0)
            ->orderBy('name')
            ->get();

        // Staff due transactions
        $myDueTransactions = Transaction::with(['customer', 'variant.product'])
            ->whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
            ->where('is_paid', false)
            ->withSum('payments as paid_amount', 'payment_transaction.allocated_amount')
            ->orderBy('transaction_date')
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                $transaction->due_amount = $transaction->total_amount - $transaction->paid_amount;
                return $transaction;
            });

        return view('dashboard.staff', [
            // Customer metrics
            'myCustomers' => $myCustomers,
            'myActiveCustomers' => $myActiveCustomers,

            // Revenue metrics
            'myMonthlyRevenue' => $myMonthlyRevenue,
            'myOutstandingPayments' => $myOutstandingPayments,

            // Performance metrics
            'myPerformance' => $myPerformance,

            // Recent data
            'myUpcomingVisits' => $myUpcomingVisits,
            'myRecentTransactions' => $myRecentTransactions,
            'myRecentPayments' => $myRecentPayments,
            'myTopProducts' => $myTopProducts,

            // Due data
            'customersWithDue' => $customersWithDue,
            'myDueTransactions' => $myDueTransactions,
        ]);
    }

    private function calculateRevenueGrowth()
    {
        $currentMonthRevenue = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('total_amount');

        $lastMonthRevenue = Transaction::whereMonth('transaction_date', now()->subMonth()->month)
            ->whereYear('transaction_date', now()->subMonth()->year)
            ->sum('total_amount');

        if ($lastMonthRevenue == 0) {
            return 0;
        }

        return round(($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100, 2);
    }

    private function getProductPerformanceChartData()
    {
        $topVariants = ProductVariant::with('product')
            ->select('product_variants.*')
            ->selectSub(function($query) {
                $query->from('transactions')
                    ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                    ->whereColumn('transactions.product_variant_id', 'product_variants.id');
            }, 'sold_quantity')
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();

        $labels = [];
        $data = [];

        foreach ($topVariants as $variant) {
            $labels[] = $variant->product->name . ' - ' . $variant->name;
            $data[] = $variant->sold_quantity;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('total_amount');

            $labels[] = $date->format('M Y');
            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getCustomerGrowthChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $count = User::customer()
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $labels[] = $date->format('M Y');
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getPaymentCollectionChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $payments = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');

            $labels[] = $date->format('M Y');
            $data[] = $payments;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
