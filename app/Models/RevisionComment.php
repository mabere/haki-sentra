<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class RevisionComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'verifiable_type',
        'verifiable_id',
        'user_id',
        'comment',
        'attachment_path',
        'attachment_name',
        'attachment_type',
        'attachment_size',
    ];

    public function verifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path) && Storage::disk('public')->exists($this->attachment_path);
    }
}
