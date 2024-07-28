<?php

namespace App\Helpers;

use App\Facades\SiteSettings;

class SiteSettingsHelper
{
    public static function get($key, $default = null)
    {
        return SiteSettings::get($key) ?? $default;
    }

    public static function getCurrency()
    {
        return self::get('currency', '£');
    }

    public static function getSiteName()
    {
        return self::get('name', config('app.name'));
    }
}