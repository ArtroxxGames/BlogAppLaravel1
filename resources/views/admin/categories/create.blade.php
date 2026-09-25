<x-app-layout title="Nueva categoría">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Nueva categoría</h1>
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Volver</a>
    </x-slot>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @include('admin.categories.partials.form', ['submit' => 'Crear categoría'])
    </form>
</x-app-layout>
