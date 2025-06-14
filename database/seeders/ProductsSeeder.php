<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $milk = Product::create([
            'name' => 'Milk',
            'description' => 'Fresh cow milk',
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $milk->id,
            'name' => '1 Liter',
            'default_price' => 60.00,
        ]);

        ProductVariant::create([
            'product_id' => $milk->id,
            'name' => 'Half Liter',
            'default_price' => 30.00,
        ]);

        ProductVariant::create([
            'product_id' => $milk->id,
            'name' => '250 ml',
            'default_price' => 15.00,
        ]);
    }
}
