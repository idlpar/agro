<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $customers = User::customer()->get();
        $staff = User::staff()->get();
        $variants = ProductVariant::all();

        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            $variant = $variants->random();
            $staffMember = $staff->random();
            $date = Carbon::now()->subDays(rand(0, 60));

            $quantity = rand(1, 10);
            $unitPrice = $variant->getPriceForCustomer($customer->id);
            $totalAmount = $quantity * $unitPrice;

            // 20% chance of partial payment
            $partialPay = rand(1, 100) <= 20 ? $totalAmount * (rand(10, 80) / 100) : 0;
            $isPaid = $partialPay === 0 ? rand(0, 1) : 0;

            Transaction::create([
                'user_id' => $customer->id,
                'product_variant_id' => $variant->id,
                'created_by' => $staffMember->id,
                'transaction_date' => $date,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'partial_pay' => $partialPay,
                'is_paid' => $isPaid,
                'notes' => rand(1, 100) <= 30 ? 'Special order' : null,
            ]);
        }
    }
}
