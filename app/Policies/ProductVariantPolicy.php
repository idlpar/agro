<?php

namespace App\Policies;

use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductVariantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function view(User $user, ProductVariant $variant): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ProductVariant $variant): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ProductVariant $variant): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, ProductVariant $variant): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, ProductVariant $variant): bool
    {
        return $user->isAdmin();
    }
}
