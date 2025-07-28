<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // customer
            $table->foreignId('product_variant_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'product_variant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_prices');
    }
};
