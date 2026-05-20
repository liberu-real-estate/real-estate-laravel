<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Book a Free Property Valuation</h2>
        <p class="mt-1 text-sm text-gray-500">Our expert valuers will visit your property and provide a detailed market appraisal.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                @if ($bookingConfirmed)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if ($googleCalendarUrl)
                            <a href="{{ $googleCalendarUrl }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Add to Google Calendar
                            </a>
                        @endif
                        @if ($outlookCalendarUrl)
                            <a href="{{ $outlookCalendarUrl }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Add to Outlook
                            </a>
                        @endif
                        @if ($confirmedAppointmentId)
                            <a href="{{ route('appointment.ics', $confirmedAppointmentId) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Download .ics (Apple / Other)
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="bookValuation" class="space-y-6">
        {{-- Property Details --}}
        <div>
            <h3 class="text-base font-semibold text-gray-800 mb-3">Property Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="propertyAddress" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="propertyAddress" wire:model="propertyAddress" placeholder="123 Main Street, London, SW1A 1AA"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('propertyAddress') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="propertyType" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Type <span class="text-red-500">*</span>
                    </label>
                    <select id="propertyType" wire:model="propertyType"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">— Select type —</option>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                        <option value="condo">Condo</option>
                        <option value="townhouse">Townhouse</option>
                        <option value="land">Land</option>
                        <option value="commercial">Commercial</option>
                        <option value="other">Other</option>
                    </select>
                    @error('propertyType') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="areaSqft" class="block text-sm font-medium text-gray-700 mb-1">Area (sq ft)</label>
                    <input type="number" id="areaSqft" wire:model="areaSqft" placeholder="e.g. 1200" min="1"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('areaSqft') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                    <input type="number" id="bedrooms" wire:model="bedrooms" placeholder="e.g. 3" min="0"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('bedrooms') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                    <input type="number" id="bathrooms" wire:model="bathrooms" placeholder="e.g. 2" min="0"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('bathrooms') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <hr class="border-gray-200">

        {{-- Appointment Date & Time --}}
        <div>
            <h3 class="text-base font-semibold text-gray-800 mb-3">Appointment Date &amp; Time</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="selectedDate" class="block text-sm font-medium text-gray-700 mb-1">
                        Preferred Date <span class="text-red-500">*</span>
                    </label>
                    <select id="selectedDate" wire:model.live="selectedDate"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">— Choose a date —</option>
                        @foreach ($availableDates as $date)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}</option>
                        @endforeach
                    </select>
                    @error('selectedDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="selectedTime" class="block text-sm font-medium text-gray-700 mb-1">
                        Preferred Time <span class="text-red-500">*</span>
                    </label>
                    <select id="selectedTime" wire:model="selectedTime"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                        {{ empty($availableTimeSlots) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        {{ empty($availableTimeSlots) ? 'disabled' : '' }}>
                        <option value="">{{ $selectedDate ? '— Choose a time —' : '— Select a date first —' }}</option>
                        @foreach ($availableTimeSlots as $slot)
                            <option value="{{ $slot }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('g:i A') }}</option>
                        @endforeach
                    </select>
                    @error('selectedTime') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <hr class="border-gray-200">

        {{-- Contact Details --}}
        <div>
            <h3 class="text-base font-semibold text-gray-800 mb-3">Your Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="userName" wire:model="userName" placeholder="John Smith"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('userName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="userEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="userEmail" wire:model="userEmail" placeholder="you@example.com"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('userEmail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="userContact" class="block text-sm font-medium text-gray-700 mb-1">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="userContact" wire:model="userContact" placeholder="+44 7700 900000"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('userContact') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea id="notes" wire:model="notes" rows="3" placeholder="Any special information about your property or requirements..."
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <p class="text-xs text-gray-500">Fields marked <span class="text-red-500">*</span> are required.</p>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed">
                <span wire:loading.remove>Request Valuation</span>
                <span wire:loading class="flex items-center gap-1.5">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Submitting...
                </span>
            </button>
        </div>
    </form>
</div>
