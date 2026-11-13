<?php

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
