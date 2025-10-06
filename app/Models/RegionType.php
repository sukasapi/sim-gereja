<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'name',
        'slug',
        'description',
        'level',
        'parent_id',
        'sort_order',
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

    // Relasi ke parent region type (hierarchical)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RegionType::class, 'parent_id');
    }

    // Relasi ke child region types
    public function children(): HasMany
    {
        return $this->hasMany(RegionType::class, 'parent_id');
    }

    // Relasi ke Region
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    // Scope untuk tipe wilayah aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk level tertentu
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Scope untuk root region types (tidak punya parent)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope untuk child region types
    public function scopeChildren($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    // Accessor untuk mendapatkan full path
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' → ', $path);
    }

    // Method untuk mendapatkan semua descendants
    public function getAllDescendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }
}
