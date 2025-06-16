<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any visits.
     */
    public function viewAny(User $user): bool
    {
        // Both admin and staff can view visits (filtered in controller)
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine whether the user can view the visit.
     */
    public function view(User $user, Visit $visit): bool
    {
        // Admin can view all visits, staff can only view their own
        return $user->isAdmin() || ($user->isStaff() && $visit->assigned_to == $user->id);
    }

    /**
     * Determine whether the user can create visits.
     */
    public function create(User $user): bool
    {
        // Both admin and staff can create visits
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine whether the user can update the visit.
     */
    public function update(User $user, Visit $visit): bool
    {
        // Admin can update any visit, staff can only update their own
        return $user->isAdmin() || ($user->isStaff() && $visit->assigned_to == $user->id);
    }

    /**
     * Determine whether the user can delete the visit.
     */
    public function delete(User $user, Visit $visit): bool
    {
        // Only admin can delete visits
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can mark a visit as complete.
     */
    public function markComplete(User $user, Visit $visit): bool
    {
        // Admin can mark any visit complete, staff can only mark their own
        return $user->isAdmin() || ($user->isStaff() && $visit->assigned_to == $user->id);
    }

    /**
     * Determine whether the user can view the collection list.
     */
    public function viewCollectionList(User $user): bool
    {
        // Both admin and staff can view collection list (filtered in controller)
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine whether the user can assign visits to other users.
     */
    public function assignToOthers(User $user): bool
    {
        // Only admin can assign visits to other users
        return $user->isAdmin();
    }
}
