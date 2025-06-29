<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        // Milk Product
        $milk = Product::create([
            'name' => 'Fresh Cow Milk',
            'description' => 'Pure, fresh milk from grass-fed cows',
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

        // Yogurt Product
        $yogurt = Product::create([
            'name' => 'Natural Yogurt',
            'description' => 'Creamy homemade yogurt',
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $yogurt->id,
            'name' => '500 gm',
            'default_price' => 80.00,
        ]);

        ProductVariant::create([
            'product_id' => $yogurt->id,
            'name' => '1 kg',
            'default_price' => 150.00,
        ]);

        // Ghee Product
        $ghee = Product::create([
            'name' => 'Pure Ghee',
            'description' => 'Traditional clarified butter',
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $ghee->id,
            'name' => '250 gm',
            'default_price' => 300.00,
        ]);

        ProductVariant::create([
            'product_id' => $ghee->id,
            'name' => '500 gm',
            'default_price' => 580.00,
        ]);

        // Paneer Product
        $paneer = Product::create([
            'name' => 'Fresh Paneer',
            'description' => 'Homemade cottage cheese',
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $paneer->id,
            'name' => '200 gm',
            'default_price' => 120.00,
        ]);

        ProductVariant::create([
            'product_id' => $paneer->id,
            'name' => '500 gm',
            'default_price' => 280.00,
        ]);

        // Butter Product
        $butter = Product::create([
            'name' => 'Fresh Butter',
            'description' => 'Creamy homemade butter',
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $butter->id,
            'name' => '100 gm',
            'default_price' => 90.00,
        ]);

        ProductVariant::create([
            'product_id' => $butter->id,
            'name' => '250 gm',
            'default_price' => 210.00,
        ]);
    }
}
