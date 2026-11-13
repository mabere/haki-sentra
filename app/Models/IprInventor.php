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
    'intellectual_property_id',
    'lecturer_id',
    'inventor_name',
    'inventor_type',
    'role',
    'inventor_order',
    'contribution_percentage',
    'affiliation',
])]
class IprInventor extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'contribution_percentage' => 'decimal:2',
            'inventor_order' => 'integer',
        ];
    }

    public function intellectualProperty(): BelongsTo
    {
        return $this->belongsTo(IntellectualProperty::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}

