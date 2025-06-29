<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All roles can view payments
    }

    public function view(User $user, Payment $payment)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStaff()) {
            return $payment->received_by === $user->id;
        }

        if ($user->isCustomer()) {
            return $payment->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function update(User $user, Payment $payment)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStaff()) {
            return $payment->received_by === $user->id;
        }

        return false;
    }

    public function delete(User $user, Payment $payment)
    {
        return $this->update($user, $payment);
    }
}
