<x-app-layout title="Editar categoría">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Editar categoría</h1>
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Volver</a>
    </x-slot>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.categories.partials.form', ['submit' => 'Guardar cambios'])
    </form>
</x-app-layout>
