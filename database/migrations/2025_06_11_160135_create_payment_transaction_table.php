<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->decimal('allocated_amount', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['payment_id', 'transaction_id']);
            $table->index('payment_id');
            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transaction');
    }
};

