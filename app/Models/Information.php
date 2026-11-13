<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Information extends Model
{
    use HasFactory;

    protected $table = 'information';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'content',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'external_link',
        'icon',
        'badge_text',
        'order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($info) {
            if (empty($info->slug)) {
                $info->slug = Str::slug($info->title) . '-' . Str::random(5);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'alur_syarat' => 'Alur & Syarat HKI',
            'template_dokumen' => 'Template Dokumen',
            'faq' => 'Tanya Jawab (FAQ)',
            'pengumuman' => 'Pengumuman Resmi',
            default => 'Panduan Umum',
        };
    }

    public function getCategoryBadgeColorAttribute(): string
    {
        return match ($this->category) {
            'alur_syarat' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'template_dokumen' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'faq' => 'bg-purple-50 text-purple-700 border-purple-200',
            'pengumuman' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return route('informasi.unduh', $this->slug);
        }

        return $this->external_link;
    }
}
