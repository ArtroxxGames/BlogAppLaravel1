<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head')
    </head>
    <body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
        @include('layouts.partials.header')

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <x-flash class="mb-6" />
            {{ $slot }}
        </main>

        @include('layouts.partials.footer')
    </body>
</html>
