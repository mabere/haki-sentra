<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'lecturer_id',
    'incentive_scheme_id',
    'claimable_type',
    'claimable_id',
    'requested_amount',
    'approved_amount',
    'bank_name',
    'bank_account_number',
    'bank_account_holder',
    'statement_letter_file',
    'transfer_proof_file',
    'status',
    'disbursed_at',
    'reviewed_by',
    'notes',
])]
class IncentiveRequest extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'requested_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'disbursed_at' => 'date',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(IncentiveScheme::class, 'incentive_scheme_id');
    }

    public function claimable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function verificationLogs(): MorphMany
    {
        return $this->morphMany(VerificationLog::class, 'verifiable');
    }

    public function revisionComments(): MorphMany
    {
        return $this->morphMany(RevisionComment::class, 'verifiable')->oldest();
    }
}

