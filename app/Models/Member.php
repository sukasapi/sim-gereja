<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'name',
        'birth_date',
        'gender',
        'address',
        'phone',
        'email',
        'photo',
        'join_date',
        'is_baptized',
        'is_sidi',
        'baptism_date',
        'sidi_date',
        'ministry_notes',
        'notes',
        'is_active',
        'father_id',
        'mother_id',
        'region_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'baptism_date' => 'date',
        'sidi_date' => 'date',
        'is_baptized' => 'boolean',
        'is_sidi' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relasi ke Church
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    // Relasi many-to-many ke Region (legacy)
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    // Relasi single ke Region
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    // Relasi ke Father
    public function father(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'father_id');
    }

    // Relasi ke Mother
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mother_id');
    }

    // Relasi ke Children (anak-anak)
    public function children()
    {
        return $this->hasMany(Member::class, 'father_id')
            ->orWhere('mother_id', $this->id);
    }

    // Scope untuk member aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk member yang sudah dibaptis
    public function scopeBaptized($query)
    {
        return $query->where('is_baptized', true);
    }

    // Scope untuk member yang sudah sidi
    public function scopeSidi($query)
    {
        return $query->where('is_sidi', true);
    }

    // Accessor untuk umur
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        
        return $this->birth_date->age;
    }

    // Accessor untuk status lengkap
    public function getStatusAttribute()
    {
        $status = [];
        
        if ($this->is_baptized) {
            $status[] = 'Baptis';
        }
        
        if ($this->is_sidi) {
            $status[] = 'Sidi';
        }
        
        return implode(', ', $status) ?: 'Belum Baptis';
    }
}
