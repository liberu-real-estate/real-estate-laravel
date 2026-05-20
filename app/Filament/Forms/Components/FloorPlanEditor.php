<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class FloorPlanEditor extends Field
{
    protected string $view = 'filament.forms.components.floor-plan-editor';

    public function getFloorPlanImageUrl(): ?string
    {
        $state = $this->getState();
        return is_array($state) && isset($state['image']) ? $state['image'] : null;
    }

    public function getFloorPlanData(): array
    {
        $state = $this->getState();
        return is_array($state) && isset($state['data']) ? $state['data'] : [];
    }
}
