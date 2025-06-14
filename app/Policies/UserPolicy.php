<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        // Admin and staff can see the user list
        return $user->isAdmin() || $user->isStaff();
    }

    public function view(User $user, User $target)
    {
        if ($user->isAdmin()) {
            return true; // Admin can view any user
        }

        if ($user->isStaff()) {
            // Staff can view any staff or customer
            return $target->isStaff() || $target->isCustomer();
        }

        return false;
    }

    public function create(User $user)
    {
        // Admin can create anyone, staff can create customers only
        return $user->isAdmin() || $user->isStaff();
    }

    public function update(User $user, User $target)
    {
        if ($user->isAdmin()) {
            return true; // Admin can update anyone
        }

        if ($user->isStaff()) {
            if ($target->isCustomer()) {
                // Staff can update any customer
                return true;
            }

            if ($target->isStaff()) {
                // Staff can update only themselves
                return $user->id === $target->id;
            }
        }

        return false;
    }

    public function delete(User $user, User $target)
    {
        // Only admin can delete users (any user)
        return $user->isAdmin();
    }


}
