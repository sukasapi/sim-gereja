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
        'is_default',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
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

    // Relasi ke Post
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Scope untuk gereja aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk gereja default
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Mutator untuk memastikan hanya satu gereja default
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($church) {
            if ($church->is_default) {
                // Set semua gereja lain menjadi tidak default
                static::where('id', '!=', $church->id)->update(['is_default' => false]);
            }
        });
    }
}
