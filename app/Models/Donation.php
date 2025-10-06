<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'donor_address',
        'donation_type', // internal/external
        'donation_category', // barang/dana
        'donation_size', // besar/kecil
        'amount',
        'quantity',
        'description',
        'item_description',
        'proof_file',
        // 'proposal_id',
        'proposal_recipient_id',
        'project_id',
        'created_by',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function proposalRecipient(): BelongsTo
    {
        return $this->belongsTo(ProposalRecipient::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
