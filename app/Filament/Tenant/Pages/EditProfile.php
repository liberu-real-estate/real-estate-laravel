<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Pages\Page;

class EditProfile extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.tenant.pages.edit-profile';

    protected static ?string $slug = 'profile';

    protected static ?string $navigationLabel = 'Edit Profile';

    protected static ?string $title = 'Edit Profile';

    public static function getRoutes(): array
    {
        return [
            static::getSlug() => static::getRoutePath(Filament::getCurrentOrDefaultPanel(), Filament::getCurrentOrDefaultPanel()),
        ];
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/tenant/profile';
    }
}