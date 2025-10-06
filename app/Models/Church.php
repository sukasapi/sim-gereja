<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Church extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
        'email',
        'website',
        'logo',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    // Relasi ke RegionType
    public function regionTypes(): HasMany
    {
        return $this->hasMany(RegionType::class);
    }

    // Relasi ke Region
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    // Relasi ke Member
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    // Relasi ke User
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Scope untuk gereja aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
