<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php($title = null)
        @include('layouts.partials.head')
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen flex-col items-center bg-gradient-to-br from-indigo-50 via-white to-fuchsia-50 pt-6 sm:justify-center sm:pt-0">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-gray-900">
                <x-application-logo class="h-10 w-10" />
                <span>{{ config('app.name') }}</span>
            </a>

            <div class="mt-6 w-full overflow-hidden bg-white px-6 py-6 shadow-md ring-1 ring-gray-100 sm:max-w-md sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
