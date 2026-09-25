<x-app-layout title="Nuevo artículo">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Nuevo artículo</h1>
        <a href="{{ route('dashboard.articles.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Volver a mis artículos</a>
    </x-slot>

    <form method="POST" action="{{ route('dashboard.articles.store') }}" enctype="multipart/form-data">
        @include('dashboard.articles.partials.form', ['submit' => 'Guardar artículo'])
    </form>
</x-app-layout>
