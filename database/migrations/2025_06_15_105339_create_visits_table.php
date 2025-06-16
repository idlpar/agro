<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('assigned_to')->constrained('users');
            $table->dateTime('scheduled_date');
            $table->string('purpose');
            $table->text('notes')->nullable();
            $table->decimal('expected_amount', 10, 2)->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->decimal('collected_amount', 10, 2)->nullable();
            $table->string('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
