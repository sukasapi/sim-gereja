<?php

namespace App\Policies;

use App\Models\Church;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChurchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin dan admin gereja bisa lihat gereja
        return $user->isSuperAdmin() || $user->isAdminGereja();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Church $church): bool
    {
        // Superadmin bisa lihat semua gereja
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin gereja hanya bisa lihat gereja mereka
        return $user->church_id === $church->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya superadmin yang bisa membuat gereja baru
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Church $church): bool
    {
        // Superadmin bisa update semua gereja
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin gereja hanya bisa update gereja mereka
        return $user->church_id === $church->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Church $church): bool
    {
        // Hanya superadmin yang bisa hapus gereja
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Church $church): bool
    {
        // Hanya superadmin yang bisa restore gereja
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Church $church): bool
    {
        // Hanya superadmin yang bisa force delete gereja
        return $user->isSuperAdmin();
    }
}
