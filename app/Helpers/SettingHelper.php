<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

use App\Models\AppSetting;

if (!function_exists('app_setting')) {
    /**
     * Get an application setting value with optional fallback.
     */
    function app_setting(string $key, $default = null)
    {
        return AppSetting::get($key, $default);
    }
}

if (!function_exists('app_setting_logo')) {
    /**
     * Get application logo or favicon URL.
     */
    function app_setting_logo(string $type = 'logo'): string
    {
        if ($type === 'favicon') {
            return AppSetting::getFaviconUrl();
        }

        return AppSetting::getLogoUrl();
    }
}

if (!function_exists('app_setting_signature')) {
    /**
     * Get Head of LPPM digital signature / stamp URL.
     */
    function app_setting_signature(): ?string
    {
        return AppSetting::getSignatureUrl();
    }
}

if (!function_exists('incentive_enabled')) {
    /**
     * Check whether the incentive module is enabled (defaults to false / nonaktif).
     */
    function incentive_enabled(): bool
    {
        $value = AppSetting::get('feature_incentive_enabled', '0');

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
