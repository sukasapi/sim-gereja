<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function finances()
    {
        return $this->hasMany(ProjectFinance::class);
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function subProjects(): HasMany
    {
        return $this->hasMany(ProjectSubProject::class);
    }

    public function workItems(): HasMany
    {
        return $this->hasMany(ProjectWorkItem::class);
    }

    public function getTotalBudgetAmountAttribute()
    {
        return $this->subProjects()->sum('budget_amount');
    }

    public function getTotalRealizationAmountAttribute()
    {
        return $this->subProjects()->sum('realization_amount');
    }
}
