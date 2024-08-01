
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Property Types</label>
            <div>
                <label class="inline-flex items-center mr-4">
                    <input type="checkbox" wire:model="propertyTypes" value="apartment" class="form-checkbox">
                    <span class="ml-2">Apartment</span>
                </label>
                <label class="inline-flex items-center mr-4">
                    <input type="checkbox" wire:model="propertyTypes" value="house" class="form-checkbox">
                    <span class="ml-2">House</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="propertyTypes" value="condo" class="form-checkbox">
                    <span class="ml-2">Condo</span>
                </label>
            </div>
        </div>

        <div class="mb-4">
            <label for="minPrice" class="block mb-2">Minimum Price</label>
            <input type="number" id="minPrice" wire:model="minPrice" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label for="maxPrice" class="block mb-2">Maximum Price</label>
            <input type="number" id="maxPrice" wire:model="maxPrice" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label for="location" class="block mb-2">Location</label>
            <input type="text" id="location" wire:model="location" class="w-full p-2 border rounded">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Preferences</button>
    </form>

    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>