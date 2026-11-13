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

#[Fillable([
    'publication_id',
    'lecturer_id',
    'author_name',
    'author_type',
    'author_role',
    'author_order',
    'affiliation',
])]
class PublicationAuthor extends Model
{
    use HasFactory;

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}

