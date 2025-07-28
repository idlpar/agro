<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            ProductsSeeder::class,
            CustomerPricesSeeder::class,
            TransactionsSeeder::class,
            PaymentsSeeder::class,
            VisitsSeeder::class,
            PaymentTransactionSeeder::class,
        ]);
    }
}
