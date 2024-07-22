<?php

namespace App\Filament\App\Resources\TenantResource\Pages;

use App\Filament\App\Resources\TenantResource;
use Filament\Resources\Pages\Page;

class EditProfile extends Page
{
    protected static string $resource = TenantResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.edit-profile';

    protected static ?string $slug = 'edit-profile';

    protected static ?string $navigationLabel = 'Edit Profile';

    protected static ?string $title = 'Edit Profile';

    public static function getRoutes(): array
    {
        return [
            static::getSlug() => static::getRoutePath(),
        ];
    }

    public static function getRoutePath(): string
    {
        return '/tenant/edit-profile';
    }
}