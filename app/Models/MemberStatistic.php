<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'statistic_type',
        'title',
        'description',
        'data',
        'period_start',
        'period_end',
        'generated_at',
    ];

    protected $casts = [
        'data' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
    ];

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }
}
