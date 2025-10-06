<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role !== 'jemaat';
    }

    public function view(User $user, Donation $donation): bool
    {
        return $user->role !== 'jemaat';
    }

    public function create(User $user): bool
    {
        return $user->role !== 'jemaat';
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->role !== 'jemaat';
    }

    public function delete(User $user, Donation $donation): bool
    {
        return $user->role !== 'jemaat';
    }
} 