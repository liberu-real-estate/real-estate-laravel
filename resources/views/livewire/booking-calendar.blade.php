<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="p-6 bg-indigo-600 text-white">
        <h2 class="text-2xl font-bold">{{ $appointmentType->name ?? 'Appointment' }} Calendar</h2>
        <p class="mt-1 text-indigo-100 text-sm">Select an available date and time to schedule your appointment.</p>
    </div>

    @if(session()->has('message'))
        <div class="mx-6 mt-4 rounded-lg bg-green-50 border border-green-200 p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="text-sm text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
        {{-- Calendar --}}
        <div class="lg:col-span-2 p-6 border-r border-gray-100">
            {{-- Month navigation --}}
            <div class="flex items-center justify-between mb-4">
                <button wire:click="previousMonth"
                    class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h3 class="text-base font-semibold text-gray-900">
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                </h3>
                <button wire:click="nextMonth"
                    class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Day headers --}}
            <div class="grid grid-cols-7 text-center mb-2">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dow)
                    <div class="text-xs font-medium text-gray-400 py-1">{{ $dow }}</div>
                @endforeach
            </div>

            {{-- Calendar grid --}}
            <div class="grid grid-cols-7 gap-1">
                @foreach($calendarDays as $day)
                    @if($day === null)
                        <div></div>
                    @else
                        <button
                            wire:click="{{ $day['isAvailable'] ? 'selectDate(\'' . $day['date'] . '\')' : '' }}"
                            class="aspect-square flex items-center justify-center text-sm rounded-lg transition
                                {{ $day['isPast'] || $day['isWeekend'] ? 'text-gray-300 cursor-not-allowed' : '' }}
                                {{ $day['isAvailable'] && !($selectedDate === $day['date']) ? 'hover:bg-indigo-50 hover:text-indigo-700 text-gray-700 cursor-pointer' : '' }}
                                {{ $selectedDate === $day['date'] ? 'bg-indigo-600 text-white font-semibold' : '' }}
                                {{ $day['isToday'] && $selectedDate !== $day['date'] ? 'border border-indigo-400 font-semibold' : '' }}"
                            {{ !$day['isAvailable'] ? 'disabled' : '' }}>
                            {{ $day['day'] }}
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-indigo-600 inline-block"></span> Selected</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded border border-indigo-400 inline-block"></span> Today</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-gray-100 inline-block"></span> Unavailable</span>
            </div>
        </div>

        {{-- Time Slots & Booking --}}
        <div class="p-6 flex flex-col">
            @if($selectedDate)
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Available times for {{ \Carbon\Carbon::parse($selectedDate)->format('D, d M Y') }}
                </h3>

                @if(count($availableTimeSlots) > 0)
                    <div class="grid grid-cols-2 gap-2 mb-6">
                        @foreach($availableTimeSlots as $slot)
                            <button wire:click="selectTimeSlot('{{ $slot }}')"
                                class="px-3 py-2 text-sm rounded-lg border transition text-center
                                    {{ $selectedTimeSlot === $slot
                                        ? 'bg-indigo-600 text-white border-indigo-600 font-semibold'
                                        : 'border-gray-300 text-gray-700 hover:border-indigo-400 hover:text-indigo-600' }}">
                                {{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center">
                        <p class="text-sm text-gray-400 text-center">No available time slots for this date.<br>Please select another date.</p>
                    </div>
                @endif

                @if($selectedTimeSlot)
                    <div class="mt-auto">
                        <div class="rounded-lg bg-indigo-50 p-3 mb-4 text-sm text-indigo-800">
                            <p class="font-medium">Your selection:</p>
                            <p>{{ \Carbon\Carbon::parse($selectedDate)->format('D, d M Y') }} at {{ \Carbon\Carbon::createFromFormat('H:i', $selectedTimeSlot)->format('g:i A') }}</p>
                        </div>
                        <button wire:click="bookAppointment"
                            class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed">
                            <span wire:loading.remove>Confirm Appointment</span>
                            <span wire:loading>Confirming...</span>
                        </button>
                    </div>
                @endif
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center text-gray-400">
                    <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="currentColor"/>
                        <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor"/>
                        <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor"/>
                        <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"/>
                    </svg>
                    <p class="text-sm">Select a date from the calendar to see available times.</p>
                </div>
            @endif
        </div>
    </div>

    @error('selectedDate') <div class="mx-6 mb-4 text-xs text-red-600">{{ $message }}</div> @enderror
    @error('selectedTimeSlot') <div class="mx-6 mb-4 text-xs text-red-600">{{ $message }}</div> @enderror
</div>
