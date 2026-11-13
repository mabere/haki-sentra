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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'user_id',
    'title',
    'ipr_type',
    'sub_type',
    'application_number',
    'application_date',
    'registration_number',
    'grant_date',
    'djki_status',
    'rights_holder',
    'certificate_file',
    'description_file',
    'verification_status',
    'verified_by',
    'verified_at',
    'verification_note',
])]
class IntellectualProperty extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'grant_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function inventors(): HasMany
    {
        return $this->hasMany(IprInventor::class)->orderBy('inventor_order');
    }

    public function verificationLogs(): MorphMany
    {
        return $this->morphMany(VerificationLog::class, 'verifiable');
    }

    public function incentiveRequests(): MorphMany
    {
        return $this->morphMany(IncentiveRequest::class, 'claimable');
    }

    public function revisionComments(): MorphMany
    {
        return $this->morphMany(RevisionComment::class, 'verifiable')->oldest();
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopeGranted($query)
    {
        return $query->where('djki_status', 'granted');
    }
}

