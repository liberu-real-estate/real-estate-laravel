<?php

namespace App\Filament\Widgets;

use App\Models\Lease;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LeaseRenewalReminderWidget extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Lease::query()
            ->where('status', 'active')
            ->where('end_date', '<=', now()->addDays(30))
            ->latest('end_date');
    }

    protected function getTableColumns(): array
    {
        return [
            TableWidget\Columns\TextColumn::make('property.address