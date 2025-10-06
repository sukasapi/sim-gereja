<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectSubProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'budget_amount',
        'realization_amount',
    ];

    protected $attributes = [
        'budget_amount' => 0,
        'realization_amount' => 0,
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
        'realization_amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function workItems(): HasMany
    {
        return $this->hasMany(ProjectWorkItem::class);
    }
}
