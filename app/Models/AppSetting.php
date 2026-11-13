<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppSetting extends Model
{
    use HasFactory;

    protected $table = 'app_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];

    public const CACHE_KEY = 'app_settings_cached_all';

    protected static function booted(): void
    {
        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get all settings from cache as key => value array.
     */
    public static function getAllCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                return static::query()->pluck('value', 'key')->all();
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $all = static::getAllCached();

        if (array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '') {
            return $all[$key];
        }

        return $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value, ?string $group = null, ?string $type = null, ?string $label = null): self
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;

        if ($group !== null) {
            $setting->group = $group;
        }
        if ($type !== null) {
            $setting->type = $type;
        }
        if ($label !== null) {
            $setting->label = $label;
        }

        $setting->save();
        static::clearCache();

        return $setting;
    }

    /**
     * Get public URL for application logo.
     */
    public static function getLogoUrl(): string
    {
        $path = static::get('app_logo');

        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return asset('logo.png');
    }

    /**
     * Get public URL for favicon / browser icon.
     */
    public static function getFaviconUrl(): string
    {
        $path = static::get('app_favicon');

        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        // If app_logo exists, use it as favicon fallback
        $logoPath = static::get('app_logo');
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return asset('storage/' . $logoPath);
        }

        return asset('logo.png');
    }

    /**
     * Get public URL for Head of LPPM digital signature / stamp.
     */
    public static function getSignatureUrl(): ?string
    {
        $path = static::get('head_lppm_signature');

        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return null;
    }
}
