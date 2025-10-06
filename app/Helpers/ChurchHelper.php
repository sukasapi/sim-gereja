<?php

namespace App\Helpers;

use App\Models\Church;
use Illuminate\Support\Facades\Auth;

class ChurchHelper
{
    /**
     * Get current church context
     */
    public static function getCurrentChurch(): ?Church
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        // Jika superadmin, return null (bisa akses semua gereja)
        if ($user->isSuperAdmin()) {
            return null;
        }
        
        // Jika user terikat ke gereja tertentu
        if ($user->church_id) {
            return Church::find($user->church_id);
        }
        
        return null;
    }
    
    /**
     * Check if user can access church data
     */
    public static function canAccessChurch(?int $churchId): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        // Superadmin bisa akses semua gereja
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // User biasa hanya bisa akses gereja mereka
        return $user->church_id === $churchId;
    }
    
    /**
     * Get church ID for current user
     */
    public static function getCurrentChurchId(): ?int
    {
        $church = self::getCurrentChurch();
        return $church ? $church->id : null;
    }
    
    /**
     * Filter query by church for non-superadmin users
     */
    public static function filterByChurch($query, string $churchColumn = 'church_id')
    {
        $user = Auth::user();
        
        if (!$user) {
            return $query->where($churchColumn, 0); // No access
        }
        
        // Superadmin bisa lihat semua
        if ($user->isSuperAdmin()) {
            return $query;
        }
        
        // User biasa hanya lihat gereja mereka
        return $query->where($churchColumn, $user->church_id);
    }
}

