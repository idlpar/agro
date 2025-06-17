<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generate()
    {
        // Generate report data
        $startDate = request('start_date', now()->subMonth());
        $endDate = request('end_date', now());

        $transactions = Transaction::with(['customer', 'variant.product'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        $payments = Payment::with(['customer', 'receiver'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();

        // Get customers (users with role 'customer')
        $customers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return view('reports.generate', [
            'transactions' => $transactions,
            'payments' => $payments,
            'customers' => $customers,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
