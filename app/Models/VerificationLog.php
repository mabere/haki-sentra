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
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'verifiable_type',
    'verifiable_id',
    'user_id',
    'status_before',
    'status_after',
    'notes',
])]
class VerificationLog extends Model
{
    use HasFactory;

    public function verifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

