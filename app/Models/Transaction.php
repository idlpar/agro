<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'product_variant_id',
        'transaction_date',
        'quantity',
        'unit_price',
        'total_amount',
        'is_paid',
        'discount_amount',
        'partial_pay',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'is_paid' => 'boolean',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'partial_pay' => 'decimal:2',
    ];

    protected $appends = ['payment_status', 'paid_amount', 'due_amount'];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_transaction')
            ->using(PaymentTransaction::class)
            ->withPivot('allocated_amount')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    // Scopes
    public function scopeUnpaid(Builder $query): Builder
    {
        return $query->where('is_paid', false);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('is_paid', true);
    }

    public function scopeBetweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // Attributes
    public function getPaidAmountAttribute(): float
    {
        return $this->payments()->sum('payment_transaction.allocated_amount');
    }

    public function getDueAmountAttribute(): float
    {
        return max($this->total_amount - $this->paid_amount, 0);
    }


    // Helpers
    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function isPartiallyPaid(): bool
    {
        return $this->paid_amount > 0 && !$this->isFullyPaid();
    }

    public function allocatePayment(Payment $payment, float $amount): float
    {
        $remainingDue = $this->due_amount;
        $amountToApply = min($amount, $remainingDue);

        if ($amountToApply <= 0) {
            return 0;
        }

        $this->payments()->attach($payment->id, [
            'allocated_amount' => $amountToApply
        ]);

        // Refresh and update status
        $this->refresh();
        $this->updatePaymentStatus();

        return $amountToApply;
    }

    public function updatePaymentStatus(): void
    {
        $totalPaid = $this->payments()->sum('payment_transaction.allocated_amount');
        $isFullyPaid = $totalPaid >= $this->total_amount;

        if ($isFullyPaid !== $this->is_paid) {
            $this->update(['is_paid' => $isFullyPaid]);
        }
    }

    public function getPaymentStatusAttribute(): string
    {
        $totalPaid = $this->payments()->sum('payment_transaction.allocated_amount');

        if ($totalPaid >= $this->total_amount) {
            return 'Paid';
        }
        if ($totalPaid > 0) {
            return 'Partial (' . number_format($totalPaid, 2) . ' Tk paid)';
        }
        return 'Due';
    }

    public function getRemainingDueAttribute(): float
    {
        $paid = $this->payments()->sum('payment_transaction.allocated_amount');
        return max($this->total_amount - $paid, 0);
    }

    public function hasPayments()
    {
        return $this->payments()->exists();
    }

}
