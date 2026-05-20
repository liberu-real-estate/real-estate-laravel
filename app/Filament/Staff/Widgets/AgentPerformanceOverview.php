<?php

namespace App\Filament\Staff\Widgets;

use App\Models\User;
use App\Models\Property;
use App\Models\Appointment;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AgentPerformanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $topAgent = User::role('agent')
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'sold');
            }])
            ->orderByDesc('properties_count')
            ->first();

        return [
            Stat::make('Total Agents', User::role('agent')->count()),
            Stat::make('Total Properties', Property::count()),
            Stat::make('Properties Sold', Property::where('status', 'sold')->count()),
            Stat::make('Appointments This Month', Appointment::whereMonth('appointment_date', now()->month)->count()),
            Stat::make('Average Agent Rating', number_format(Review::avg('rating'), 2)),
            Stat::make('Top Performing Agent', $topAgent ? $topAgent->name : 'N/A')
                ->description($topAgent ? $topAgent->properties_count . ' properties sold' : ''),
        ];
    }
}