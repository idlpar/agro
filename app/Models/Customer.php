<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends User
{
    //
    protected $casts = [
        'last_transaction_date' => 'datetime',
    ];
}
