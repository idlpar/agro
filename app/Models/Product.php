<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->variants()->whereNull('deleted_at');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}
