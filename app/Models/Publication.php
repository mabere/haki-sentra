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
    'publication_type',
    'journal_name',
    'doi',
    'issn_isbn',
    'volume',
    'issue',
    'pages',
    'publication_year',
    'publication_date',
    'url',
    'indexation',
    'quartile',
    'sinta_grade',
    'citation_count',
    'file_path',
    'loa_path',
    'verification_status',
    'verified_by',
    'verified_at',
    'verification_note',
])]
class Publication extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'publication_date' => 'date',
            'citation_count' => 'integer',
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

    public function authors(): HasMany
    {
        return $this->hasMany(PublicationAuthor::class)->orderBy('author_order');
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

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }
}

