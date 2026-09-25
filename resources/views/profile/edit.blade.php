<x-app-layout :title="__('Profile')">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Profile') }}</h1>
        <a href="{{ route('authors.show', $user) }}" class="text-sm text-indigo-600 hover:underline">Ver mi perfil público →</a>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
