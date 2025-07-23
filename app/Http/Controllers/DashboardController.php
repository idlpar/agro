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
        $cacheKey = 'admin_dashboard_data';
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 15, function () {
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
            $lastMonthTotalRevenue = Transaction::whereYear('transaction_date', now()->subMonth()->year)
                ->whereMonth('transaction_date', now()->subMonth()->month)
                ->sum('total_amount') ?? 0;

            $thisMonthTotalRevenue = Transaction::whereYear('transaction_date', now()->year)
                ->whereMonth('transaction_date', now()->month)
                ->sum('total_amount') ?? 0;

            $totalRevenue = Transaction::sum('total_amount') ?? 0;
            $monthlyRevenue = Transaction::whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year)
                ->sum('total_amount') ?? 0;

            $revenueGrowth = $this->calculateRevenueGrowth($thisMonthTotalRevenue, $lastMonthTotalRevenue);

            // Outstanding payments
            $outstandingPayments = DB::table('transactions')
                ->select(DB::raw('SUM(total_amount - COALESCE((SELECT SUM(allocated_amount) FROM payment_transaction WHERE payment_transaction.transaction_id = transactions.id), 0)) as outstanding'))
                ->where('is_paid', false)
                ->value('outstanding') ?? 0;

            // Payment stats
            $lastMonthTotalPayments = Payment::whereYear('payment_date', now()->subMonth()->year)
                ->whereMonth('payment_date', now()->subMonth()->month)
                ->sum('amount') ?? 0;
            $totalPayments = Payment::sum('amount') ?? 0;
            $monthlyPayments = Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount') ?? 0;

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
            $lastMonth = now()->subMonth();
            $topSellingVariants = ProductVariant::with('product')
                ->select('product_variants.*')
                ->selectSub(function($query) use ($lastMonth) {
                    $query->from('transactions')
                        ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                        ->whereColumn('transactions.product_variant_id', 'product_variants.id')
                        ->whereYear('transaction_date', $lastMonth->year)
                        ->whereMonth('transaction_date', $lastMonth->month);
                }, 'sold_quantity_last_month')
                ->orderByDesc('sold_quantity_last_month')
                ->paginate(5)
                ->through(function ($variant) {
                    $variant->sold_quantity_this_month = Transaction::where('product_variant_id', $variant->id)
                        ->whereYear('transaction_date', now()->year)
                        ->whereMonth('transaction_date', now()->month)
                        ->sum('quantity') ?? 0;
                    return $variant;
                });

            // Recent data
            $recentTransactions = Transaction::with(['customer', 'variant.product', 'creator'])
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
                ->paginate(10)
                ->map(function ($transaction) {
                    $transaction->due_amount = $transaction->total_amount - ($transaction->paid_amount ?? 0);
                    return $transaction;
                });

            // Chart data
            $revenueChartData = $this->getRevenueChartData();
            $customerGrowthChartData = $this->getCustomerGrowthChartData();
            $paymentCollectionChartData = $this->getPaymentCollectionChartData();
            $productPerformanceChartData = $this->getProductPerformanceChartData();
            $historicalProductSalesChartData = $this->getHistoricalProductSalesChartData();

            return [
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
                'lastMonthTotalRevenue' => $lastMonthTotalRevenue,
                'thisMonthTotalRevenue' => $thisMonthTotalRevenue,
                'outstandingPayments' => $outstandingPayments,
                'recentTransactions' => $recentTransactions,

                // Payment metrics
                'totalPayments' => $totalPayments,
                'monthlyPayments' => $monthlyPayments,
                'lastMonthTotalPayments' => $lastMonthTotalPayments,
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
                'historicalProductSalesChartData' => $historicalProductSalesChartData,
            ];
        });

        return view('dashboard.admin', $data);
    }

    protected function staffDashboard()
    {
        $staffId = auth()->id();
        $cacheKey = 'staff_dashboard_data_' . $staffId;

        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 15, function () use ($staffId) {
            // Staff-specific customer stats
            $myCustomers = User::customer()
                ->where('created_by', $staffId)
                ->count();

            $myActiveCustomers = User::customer()
                ->where('created_by', $staffId)
                ->has('transactions')
                ->count();

            // Staff revenue stats
            $myLastMonthRevenue = Transaction::whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
                ->whereYear('transaction_date', now()->subMonth()->year)
                ->whereMonth('transaction_date', now()->subMonth()->month)
                ->sum('total_amount') ?? 0;

            $myThisMonthRevenue = Transaction::whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
                ->whereYear('transaction_date', now()->year)
                ->whereMonth('transaction_date', now()->month)
                ->sum('total_amount') ?? 0;

            $myRevenueGrowth = $this->calculateRevenueGrowth($myThisMonthRevenue, $myLastMonthRevenue);

            // Staff payment stats
            $myLastMonthPayments = Payment::whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
                ->whereYear('payment_date', now()->subMonth()->year)
                ->whereMonth('payment_date', now()->subMonth()->month)
                ->sum('amount') ?? 0;

            $myThisMonthPayments = Payment::whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
                ->whereYear('payment_date', now()->year)
                ->whereMonth('payment_date', now()->month)
                ->sum('amount') ?? 0;

            $myPaymentGrowth = $this->calculateRevenueGrowth($myThisMonthPayments, $myLastMonthPayments);

            // Outstanding payments for staff's customers
            $myOutstandingPayments = DB::table('transactions')
                ->select(DB::raw('SUM(total_amount - COALESCE((SELECT SUM(allocated_amount) FROM payment_transaction WHERE payment_transaction.transaction_id = transactions.id), 0)) as outstanding'))
                ->where('is_paid', false)
                ->whereExists(function ($query) use ($staffId) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereColumn('users.id', 'transactions.user_id')
                        ->where('users.created_by', $staffId);
                })
                ->value('outstanding') ?? 0;

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
            $lastMonth = now()->subMonth();
            $myTopProducts = ProductVariant::with('product')
                ->select('product_variants.*')
                ->selectSub(function($query) use ($staffId, $lastMonth) {
                    $query->from('transactions')
                        ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                        ->whereColumn('transactions.product_variant_id', 'product_variants.id')
                        ->whereYear('transaction_date', $lastMonth->year)
                        ->whereMonth('transaction_date', $lastMonth->month)
                        ->whereExists(function ($subQuery) use ($staffId) {
                            $subQuery->select(DB::raw(1))
                                ->from('users')
                                ->whereColumn('users.id', 'transactions.user_id')
                                ->where('users.created_by', $staffId);
                        });
                }, 'sold_quantity_last_month')
                ->orderByDesc('sold_quantity_last_month')
                ->paginate(5)
                ->through(function ($variant) use ($staffId) {
                    $variant->sold_quantity_this_month = Transaction::where('product_variant_id', $variant->id)
                        ->whereYear('transaction_date', now()->year)
                        ->whereMonth('transaction_date', now()->month)
                        ->whereExists(function ($subQuery) use ($staffId) {
                            $subQuery->select(DB::raw(1))
                                ->from('users')
                                ->whereColumn('users.id', 'transactions.user_id')
                                ->where('users.created_by', $staffId);
                        })
                        ->sum('quantity') ?? 0;
                    return $variant;
                });

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
                ->paginate(10)
                ->map(function ($transaction) {
                    $transaction->due_amount = $transaction->total_amount - ($transaction->paid_amount ?? 0);
                    return $transaction;
                });

            // Chart data for staff dashboard
            $myRevenueChartData = $this->getStaffRevenueChartData($staffId);
            $myCustomerGrowthChartData = $this->getStaffCustomerGrowthChartData($staffId);
            $myProductPerformanceChartData = $this->getStaffProductPerformanceChartData($staffId);

            return [
                // Customer metrics
                'myCustomers' => $myCustomers,
                'myActiveCustomers' => $myActiveCustomers,

                // Revenue metrics
                'myLastMonthRevenue' => $myLastMonthRevenue,
                'myThisMonthRevenue' => $myThisMonthRevenue,
                'myRevenueGrowth' => $myRevenueGrowth,
                'myOutstandingPayments' => $myOutstandingPayments,

                // Payment metrics
                'myLastMonthPayments' => $myLastMonthPayments,
                'myThisMonthPayments' => $myThisMonthPayments,
                'myPaymentGrowth' => $myPaymentGrowth,

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

                // Chart data
                'myRevenueChartData' => $myRevenueChartData,
                'myCustomerGrowthChartData' => $myCustomerGrowthChartData,
                'myProductPerformanceChartData' => $myProductPerformanceChartData,
            ];
        });

        return view('dashboard.staff', $data);
    }

    private function calculateRevenueGrowth($currentMonthRevenue, $lastMonthRevenue)
    {
        if ($lastMonthRevenue == 0) {
            return 0;
        }

        return round(($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100, 2);
    }

    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('total_amount') ?? 0;

            $labels[] = $date->format('M Y');
            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getStaffRevenueChartData($staffId)
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::whereHas('customer', function($query) use ($staffId) {
                $query->where('created_by', $staffId);
            })
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('total_amount') ?? 0;

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
        $monthlyData = [];
        $yearlyData = [];
        $monthlyLabels = [];
        $yearlyLabels = [];

        // Monthly data (last 6 months)
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $count = User::customer()
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $monthlyLabels[] = $date->format('M\'y'); // Format as "Jul'24"
            $monthlyData[] = $count;
        }

        // Yearly data (last 5 years)
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i);
            $start = $year->copy()->startOfYear();
            $end = $year->copy()->endOfYear();

            $count = User::customer()
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $yearlyLabels[] = $year->format('Y');
            $yearlyData[] = $count;
        }

        return [
            'labels' => [
                'monthly' => $monthlyLabels,
                'yearly' => $yearlyLabels
            ],
            'data' => [
                'monthly' => $monthlyData,
                'yearly' => $yearlyData
            ]
        ];
    }

    private function getStaffCustomerGrowthChartData($staffId)
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $count = User::customer()
                ->where('created_by', $staffId)
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
        $monthlyData = [];
        $yearlyData = [];
        $monthlyLabels = [];
        $yearlyLabels = [];

        // Monthly data (last 6 months)
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $payments = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount') ?? 0;

            $monthlyLabels[] = $date->format('M\'y'); // Format as "Jul'24"
            $monthlyData[] = $payments;
        }

        // Yearly data (last 5 years)
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i);
            $payments = Payment::whereYear('payment_date', $year->year)
                ->sum('amount') ?? 0;

            $yearlyLabels[] = $year->format('Y');
            $yearlyData[] = $payments;
        }

        return [
            'labels' => [
                'monthly' => $monthlyLabels,
                'yearly' => $yearlyLabels
            ],
            'data' => [
                'monthly' => $monthlyData,
                'yearly' => $yearlyData
            ]
        ];
    }
    private function getProductPerformanceChartData()
    {
        $topVariants = ProductVariant::with('product')
            ->select('product_variants.*')
            ->selectSub(function($query) {
                $query->from('transactions')
                    ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                    ->whereColumn('transactions.product_variant_id', 'product_variants.id')
                    ->where('transactions.transaction_date', '>=', now()->subDays(30));
            }, 'sold_quantity')
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();

        $labels = [];
        $datasets = [];
        $days = 30;

        // Generate labels for the last 30 days
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
        }

        // Prepare datasets for each top variant
        foreach ($topVariants as $index => $variant) {
            $data = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $quantity = Transaction::where('product_variant_id', $variant->id)
                    ->whereDate('transaction_date', $date)
                    ->sum('quantity') ?? 0;
                $data[] = $quantity;
            }
            $datasets[] = [
                'label' => $variant->product->name . ' - ' . $variant->name,
                'data' => $data
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

    private function getStaffProductPerformanceChartData($staffId)
    {
        $topVariants = ProductVariant::with('product')
            ->select('product_variants.*')
            ->selectSub(function($query) use ($staffId) {
                $query->from('transactions')
                    ->selectRaw('COALESCE(SUM(transactions.quantity), 0)')
                    ->whereColumn('transactions.product_variant_id', 'product_variants.id')
                    ->where('transactions.transaction_date', '>=', now()->subDays(30))
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

        $labels = [];
        $datasets = [];
        $days = 30;

        // Generate labels for the last 30 days
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
        }

        // Prepare datasets for each top variant
        foreach ($topVariants as $index => $variant) {
            $data = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $quantity = Transaction::where('product_variant_id', $variant->id)
                    ->whereDate('transaction_date', $date)
                    ->whereExists(function ($query) use ($staffId) {
                        $query->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('users.id', 'transactions.user_id')
                            ->where('users.created_by', $staffId);
                    })
                    ->sum('quantity') ?? 0;
                $data[] = $quantity;
            }
            $datasets[] = [
                'label' => $variant->product->name . ' - ' . $variant->name,
                'data' => $data
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }

    private function getHistoricalProductSalesChartData()
    {
        $topVariants = ProductVariant::with('product')
            ->select('product_variants.*')
            ->selectSub(function($query) {
                $query->from('transactions')
                    ->selectRaw('COALESCE(SUM(transactions.total_amount), 0)')
                    ->whereColumn('transactions.product_variant_id', 'product_variants.id');
            }, 'total_sales')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        $monthlyData = [];
        $quarterlyData = [];
        $yearlyData = [];
        $monthlyLabels = [];
        $quarterlyLabels = [];
        $yearlyLabels = [];

        // Monthly data (last 12 months)
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            foreach ($topVariants as $variant) {
                $sales = Transaction::where('product_variant_id', $variant->id)
                    ->whereMonth('transaction_date', $date->month)
                    ->whereYear('transaction_date', $date->year)
                    ->sum('total_amount') ?? 0;
                $monthlyData[$variant->id][] = $sales;
            }
        }

        // Quarterly data (last 4 quarters)
        for ($i = 3; $i >= 0; $i--) {
            $start = now()->subQuarters($i)->startOfQuarter();
            $end = now()->subQuarters($i)->endOfQuarter();
            $quarterlyLabels[] = "Q" . $start->quarter . " " . $start->year;
            foreach ($topVariants as $variant) {
                $sales = Transaction::where('product_variant_id', $variant->id)
                    ->whereBetween('transaction_date', [$start, $end])
                    ->sum('total_amount') ?? 0;
                $quarterlyData[$variant->id][] = $sales;
            }
        }

        // Yearly data (last 5 years)
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            $yearlyLabels[] = $year;
            foreach ($topVariants as $variant) {
                $sales = Transaction::where('product_variant_id', $variant->id)
                    ->whereYear('transaction_date', $year)
                    ->sum('total_amount') ?? 0;
                $yearlyData[$variant->id][] = $sales;
            }
        }

        $datasets = [];
        foreach ($topVariants as $index => $variant) {
            $datasets[] = [
                'label' => $variant->product->name . ' - ' . $variant->name,
                'monthly' => $monthlyData[$variant->id] ?? [],
                'quarterly' => $quarterlyData[$variant->id] ?? [],
                'yearly' => $yearlyData[$variant->id] ?? []
            ];
        }

        return [
            'labels' => [
                'monthly' => $monthlyLabels,
                'quarterly' => $quarterlyLabels,
                'yearly' => $yearlyLabels
            ],
            'datasets' => $datasets
        ];
    }
}
