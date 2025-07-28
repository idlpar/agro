<?php

namespace Database\Seeders;

use App\Models\CustomerPrice;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerPricesSeeder extends Seeder
{
    public function run()
    {
        $customers = User::customer()->get();
        $variants = ProductVariant::all();

        foreach ($customers as $customer) {
            foreach ($variants as $variant) {
                // Give some customers special prices (about 30% of cases)
                if (rand(1, 100) <= 30) {
                    $discount = rand(5, 20); // 5-20% discount
                    $specialPrice = $variant->default_price * (1 - ($discount / 100));

                    CustomerPrice::create([
                        'user_id' => $customer->id,
                        'product_variant_id' => $variant->id,
                        'price' => round($specialPrice, 2),
                    ]);
                }
            }
        }
    }
}
