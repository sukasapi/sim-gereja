<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectWorkSubItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_work_item_id',
        'name',
        'description',
        'budget_amount',
        'realization_amount',
        'status',
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
        'realization_amount' => 'decimal:2',
    ];

    public function workItem(): BelongsTo
    {
        return $this->belongsTo(ProjectWorkItem::class);
    }
} 