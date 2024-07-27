<?php

namespace App\Helpers;

use App\Models\SiteSetting;
use App\Models\SiteSettings;

class SettingsHelper
{
    public static function getDefaultCurrency()
    {
        $setting = SiteSettings::first();
        return $setting ? $setting->currency : 'USD'; 
    }
}