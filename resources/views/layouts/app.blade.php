<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head')
    </head>
    <body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
        @include('layouts.partials.header')

        @isset($header)
            <div class="border-b border-gray-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <x-flash class="mb-6" />
            {{ $slot }}
        </main>
    </body>
</html>
