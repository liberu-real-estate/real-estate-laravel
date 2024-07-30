@extends('layouts.app')

@section('content')
    <div class="min-h-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Please sign in to access the admin panel.') }}
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('Email') }}
                    </label>
                    <input id="email" type="email" name="email" required autofocus
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('Password') }}
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Login Button -->
                <div class="flex items-center justify-between mt-4">
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        {{ __('Log in') }}
                    </button>
                </div>

                <!-- Forgot Password Link -->
                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('password.request') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Forgot your password?') }}
                    </a>

                    <!-- Register Button -->
                    <a href="{{ route('register') }}" class="text-green-600 hover:underline">
                        {{ __('Sign up') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
