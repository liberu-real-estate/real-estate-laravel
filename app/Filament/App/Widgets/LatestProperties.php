<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Property;

class LatestProperties extends Widget
{
    protected static string $view = 'filament.widgets.latest-properties';

    public function getLatestProperties()
    {
        return Property::latest()->take(5)->get();
    }
}