<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'region_type_id',
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Church
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    // Relasi ke RegionType
    public function regionType(): BelongsTo
    {
        return $this->belongsTo(RegionType::class);
    }

    // Relasi ke parent region (hierarchical)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    // Relasi ke child regions
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    // Relasi many-to-many ke Member
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class);
    }

    // Scope untuk wilayah aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk root regions (tidak punya parent)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope untuk child regions
    public function scopeChildren($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }
}
