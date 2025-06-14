<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'address', 'password', 'role', 'created_by'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function createdStaff()
    {
        return $this->createdUsers()->where('role', 'staff');
    }

    public function createdCustomers()
    {
        return $this->createdUsers()->where('role', 'customer');
    }

    public function customerPrices()
    {
        return $this->hasMany(CustomerPrice::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Role helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // Scopes
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    public function scopeCustomer($query)
    {
        return $query->where('role', 'customer');
    }

    public static function roles()
    {
        return ['admin', 'staff', 'customer'];
    }

    public function dueTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('is_paid', false)
            ->whereNotNull('transaction_date')
            ->orderBy('transaction_date', 'desc');
    }

    public function allTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->orderBy('transaction_date', 'desc');
    }

}
