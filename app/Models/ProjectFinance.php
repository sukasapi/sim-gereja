<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFinance extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'type',
        'amount',
        'description',
        'transaction_proof',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
