<div>
    <h2>Document Generator</h2>
    
    <div class="mb-4">
        <label for="template" class="block mb-2">Select Template</label>
        <select id="template" wire:model="selectedTemplate" wire:change="selectTemplate($event.target.value)" class="w-full p-2 border rounded">
            <option value="">Select a template</option>
            @foreach($templates as $template)
                <option value="{{ $template->id }}">{{ $template->name }}</option>
            @endforeach
        </select>
    </div>

    @if($selectedTemplate)
        <div class="mb-4">
            <h3>Customize Fields</h3>
            @foreach($customFields as $field => $value)
                <div class="mb-2">
                    <label for="{{ $field }}" class="block">{{ ucfirst($field) }}</label>
                    <input type="text" id="{{ $field }}" wire:model="customFields.{{ $field }}" class="w-full p-2 border rounded">
                </div>
            @endforeach
        </div>

        <button wire:click="generateDocument" class="px-4 py-2 bg-blue-500 text-white rounded">Generate Document</button>
    @endif

    @if($generatedDocument)
        <div class="mt-4">
            <h3>Generated Document</h3>
            <div class="p-4 border rounded">
                {!! $generatedDocument !!}
            </div>
        </div>
    @endif
</div>