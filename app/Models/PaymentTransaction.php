<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PaymentTransaction extends Pivot
{
    protected $table = 'payment_transaction';

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];

    protected $fillable = [
        'payment_id',
        'transaction_id',
        'allocated_amount'
    ];
}
