<x-app-layout title="Editar artículo">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Editar artículo</h1>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('articles.show', $article) }}" class="text-indigo-600 hover:underline">Ver artículo</a>
            <a href="{{ route('dashboard.articles.index') }}" class="text-gray-600 hover:text-gray-900">← Volver</a>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('dashboard.articles.update', $article) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('dashboard.articles.partials.form', ['submit' => 'Guardar cambios'])
    </form>
</x-app-layout>
