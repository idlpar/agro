<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'received_by',
        'payment_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class, 'payment_transaction')
            ->using(PaymentTransaction::class)
            ->withPivot('allocated_amount')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    public function getIsAllocatedAttribute(): bool
    {
        return $this->allocated_amount > 0;
    }

    // Scopes
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('user_id', $customerId);
    }

    public function scopeForReceiver($query, $receiverId)
    {
        return $query->where('received_by', $receiverId);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopeAllocated($query)
    {
        return $query->has('transactions');
    }

    public function scopeUnallocated($query)
    {
        return $query->doesntHave('transactions');
    }

    public function scopeAmountRange($query, $min, $max)
    {
        return $query->whereBetween('amount', [$min, $max]);
    }


    public function getAllocatedAmountAttribute(): float
    {
        return $this->transactions()->sum('payment_transaction.allocated_amount');
    }

    public function getUnallocatedAmountAttribute(): float
    {
        return max($this->amount - $this->allocated_amount, 0);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', 'paid');
    }
}
