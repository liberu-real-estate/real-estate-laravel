<div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('contact.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" class="form-input rounded-md shadow-sm w-full" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" class="form-input rounded-md shadow-sm w-full" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message:</label>
            <textarea class="form-textarea rounded-md shadow-sm w-full" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
            @error('message')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Send Message</button>
    </form>
</div>

<div class="container">
    <h2>Contact Us</h2>
    <form action="{{ route('contact.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
