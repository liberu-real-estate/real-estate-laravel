<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Activity;

class RecentActivityList extends Component
{
    public function render()
    {
        $recentActivities = Activity::with(['lead', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.recent-activity-list', [
            'activities' => $recentActivities,
        ]);
    }
}
