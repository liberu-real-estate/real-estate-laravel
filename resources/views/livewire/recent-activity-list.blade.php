<div>
    <ul class="divide-y divide-gray-200">
        @foreach($activities as $activity)
            <li class="py-4">
                <div class="flex space-x-3">
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium">{{ $activity->type }}</h3>
                            <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                        @if($activity->lead)
                            <p class="text-sm text-gray-500">Lead: {{ $activity->lead->name }}</p>
                        @endif
                        @if($activity->user)
                            <p class="text-sm text-gray-500">User: {{ $activity->user->name }}</p>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
