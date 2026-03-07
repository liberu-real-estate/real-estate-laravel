<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{

    public string $site_name = 'Liberu Real Estate';
    public string $site_email = 'info@liberu.co.uk';
    public ?string $site_phone = null;
    public ?string $site_address = null;
    public ?string $site_country = null;
    public string $site_currency = '£';
    public string $site_default_language = 'en';
    public ?string $facebook_url = null;
    public ?string $twitter_url = null;
    public ?string $github_url = null;
    public ?string $youtube_url = null;
    public string $footer_copyright = '© Liberu Real Estate. All rights reserved.';

    public static function group(): string
    {
        return 'general';
    }
}