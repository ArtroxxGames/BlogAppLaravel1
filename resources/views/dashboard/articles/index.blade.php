<x-app-layout title="Mis artículos">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Mis artículos</h1>
        <div class="flex items-center gap-3">
            <form method="GET" role="search">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar…" aria-label="Buscar en mis artículos"
                       class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </form>
            <a href="{{ route('dashboard.articles.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Nuevo artículo</a>
        </div>
    </x-slot>

    @if ($articles->isEmpty())
        <x-empty-state>
            {{ request('q') ? 'Ningún artículo coincide con tu búsqueda.' : 'Todavía no has escrito ningún artículo.' }}
        </x-empty-state>
    @else
        <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th scope="col" class="px-5 py-3">Título</th>
                        <th scope="col" class="px-5 py-3">Categoría</th>
                        <th scope="col" class="px-5 py-3">Estado</th>
                        <th scope="col" class="px-5 py-3">Comentarios</th>
                        <th scope="col" class="px-5 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($articles as $article)
                        <tr class="hover:bg-gray-50">
                            <td class="max-w-md px-5 py-4">
                                <a href="{{ route('articles.show', $article) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $article->title }}</a>
                                <p class="text-xs text-gray-500">Actualizado {{ $article->updated_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $article->category->name }}</td>
                            <td class="px-5 py-4">
                                @if ($article->isPublished())
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Publicado</span>
                                @elseif ($article->isScheduled())
                                    <span class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700" title="{{ $article->published_at->translatedFormat('d/m/Y H:i') }}">Programado</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">Borrador</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $article->comments_count }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('dashboard.articles.edit', $article) }}" class="font-medium text-indigo-600 hover:underline">Editar</a>
                                <form method="POST" action="{{ route('dashboard.articles.destroy', $article) }}" class="ms-4 inline" onsubmit="return confirm('¿Eliminar este artículo? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-medium text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $articles->links() }}</div>
    @endif
</x-app-layout>
