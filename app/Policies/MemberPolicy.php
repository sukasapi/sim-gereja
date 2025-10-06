<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin, admin gereja, dan admin komisi bisa lihat jemaat
        return $user->isSuperAdmin() || $user->isAdminGereja() || $user->isAdminKomisi();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Member $member): bool
    {
        // Superadmin bisa lihat semua jemaat
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin gereja dan admin komisi hanya bisa lihat jemaat di gereja mereka
        return $user->church_id === $member->church_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Superadmin dan admin gereja bisa membuat jemaat baru
        return $user->isSuperAdmin() || $user->isAdminGereja();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Member $member): bool
    {
        // Superadmin bisa update semua jemaat
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin gereja dan admin komisi hanya bisa update jemaat di gereja mereka
        return $user->church_id === $member->church_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Member $member): bool
    {
        // Superadmin dan admin gereja bisa hapus jemaat
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        return $user->isAdminGereja() && $user->church_id === $member->church_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Member $member): bool
    {
        // Superadmin dan admin gereja bisa restore jemaat
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        return $user->isAdminGereja() && $user->church_id === $member->church_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Member $member): bool
    {
        // Hanya superadmin yang bisa force delete jemaat
        return $user->isSuperAdmin();
    }
}
