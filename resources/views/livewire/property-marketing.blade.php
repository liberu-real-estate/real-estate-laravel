<div>
    <h2 class="text-2xl font-bold mb-4">Property Marketing</h2>

    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Listing Syndication</h3>
        <button wire:click="syndicateProperty" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Syndicate Property
        </button>

        @if(!empty($syndicationResults))
            <div class="mt-4">
                <h4 class="text-lg font-semibold mb-2">Syndication Results:</h4>
                <ul>
                    @foreach($syndicationResults as $platform => $result)
                        <li>{{ ucfirst($platform) }}: {{ $result['status'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="mb-6">
        <h3 class="text-xl font-semibold mb-2">Social Media Sharing</h3>
        <div class="mb-4">
            @foreach($socialMediaPlatforms as $platform)
                <label class="inline-flex items-center mr-4">
                    <input type="checkbox" wire:model="selectedPlatforms" value="{{ $platform }}" class="form-checkbox">
                    <span class="ml-2">{{ ucfirst($platform) }}</span>
                </label>
            @endforeach
        </div>
        <button wire:click="shareOnSocialMedia" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Share on Social Media
        </button>
    </div>
</div>