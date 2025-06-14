<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'default_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customerPrices()
    {
        return $this->hasMany(CustomerPrice::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPriceForCustomer($customerId)
    {
        $customPrice = $this->customerPrices()
            ->where('user_id', $customerId)
            ->first();

        return $customPrice ? $customPrice->price : $this->default_price;
    }
}
