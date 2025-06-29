<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All roles can view transactions
    }

    public function view(User $user, Transaction $transaction)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStaff()) {
            return $transaction->created_by === $user->id;
        }

        if ($user->isCustomer()) {
            return $transaction->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function update(User $user, Transaction $transaction)
    {
        // Admin can always update
        if ($user->isAdmin()) {
            return true;
        }

        // Staff can update their own transactions
        if ($user->isStaff()) {
            return $transaction->created_by === $user->id;
        }

        return false;
    }

    public function delete(User $user, Transaction $transaction)
    {
        return $this->update($user, $transaction);
    }
}
