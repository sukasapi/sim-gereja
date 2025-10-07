<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdminGereja();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // Superadmin dapat melihat semua post
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin gereja hanya dapat melihat post gerejanya
        return $user->isAdminGereja() && $user->church_id === $post->church_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin gereja yang dapat membuat post
        return $user->isAdminGereja();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Hanya admin gereja yang dapat mengupdate post
        return $user->isAdminGereja() && $user->church_id === $post->church_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Hanya admin gereja yang dapat menghapus post
        return $user->isAdminGereja() && $user->church_id === $post->church_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can toggle status of the model.
     */
    public function toggleStatus(User $user, Post $post): bool
    {
        // Hanya superadmin yang dapat mengubah status posting
        return $user->isSuperAdmin();
    }
}
