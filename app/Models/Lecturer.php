<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'department_id',
    'nidn',
    'nip',
    'front_title',
    'back_title',
    'academic_rank',
    'sinta_id',
    'scopus_id',
    'google_scholar_id',
    'orcid_id',
    'phone',
    'sinta_score_overall',
    'sinta_score_3year',
    'google_scholar_citations',
    'google_scholar_h_index',
    'google_scholar_i10_index',
    'last_synced_at',
])]
class Lecturer extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'last_synced_at' => 'datetime',
            'sinta_score_overall' => 'integer',
            'sinta_score_3year' => 'integer',
            'google_scholar_citations' => 'integer',
            'google_scholar_h_index' => 'integer',
            'google_scholar_i10_index' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function publicationAuthors(): HasMany
    {
        return $this->hasMany(PublicationAuthor::class);
    }

    public function publications(): BelongsToMany
    {
        return $this->belongsToMany(Publication::class, 'publication_authors', 'lecturer_id', 'publication_id');
    }

    public function iprInventors(): HasMany
    {
        return $this->hasMany(IprInventor::class);
    }

    public function intellectualProperties(): BelongsToMany
    {
        return $this->belongsToMany(IntellectualProperty::class, 'ipr_inventors', 'lecturer_id', 'intellectual_property_id');
    }

    public function incentiveRequests(): HasMany
    {
        return $this->hasMany(IncentiveRequest::class);
    }

    public function getFullNameAttribute(): string
    {
        $name = $this->user ? $this->user->name : '';
        $front = $this->front_title ? trim($this->front_title) . ' ' : '';
        $back = $this->back_title ? ', ' . trim($this->back_title) : '';

        return $front . $name . $back;
    }

    public function getSintaUrlAttribute(): ?string
    {
        return $this->sinta_id ? "https://sinta.kemdiktisaintek.go.id/authors/profile/{$this->sinta_id}" : null;
    }

    public function getScholarUrlAttribute(): ?string
    {
        return $this->google_scholar_id ? "https://scholar.google.com/citations?user={$this->google_scholar_id}" : null;
    }

    public function getScopusUrlAttribute(): ?string
    {
        return $this->scopus_id ? "https://www.scopus.com/authid/detail.uri?authorId={$this->scopus_id}" : null;
    }
}

