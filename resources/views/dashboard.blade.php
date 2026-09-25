<x-app-layout title="Panel">
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hola, {{ Str::before(Auth::user()->name, ' ') }} 👋</h1>
            <p class="text-sm text-gray-500">Este es el resumen de tu actividad como autor.</p>
        </div>
        <a href="{{ route('dashboard.articles.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Nuevo artículo</a>
    </x-slot>

    <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            'Publicados' => $stats['published'],
            'Borradores y programados' => $stats['drafts'],
            'Comentarios recibidos' => $stats['comments'],
            'Valoración media' => $stats['comments'] ? $stats['rating'].' / 5' : '—',
        ] as $label => $value)
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <dt class="text-sm text-gray-500">{{ $label }}</dt>
                <dd class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</dd>
            </div>
        @endforeach
    </dl>

    <section class="mt-10">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Últimos comentarios en tus artículos</h2>
            <a href="{{ route('dashboard.articles.index') }}" class="text-sm text-indigo-600 hover:underline">Ver mis artículos →</a>
        </div>

        <div class="mt-4 divide-y divide-gray-100 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
            @forelse ($latestComments as $comment)
                <div class="flex items-start gap-4 p-5">
                    <x-avatar :user="$comment->author" size="h-9 w-9 text-xs" />
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                            <span class="font-medium">{{ $comment->author->name }}</span>
                            <x-star-rating :value="$comment->rating" />
                            <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 line-clamp-2 text-sm text-gray-700">{{ $comment->body }}</p>
                        <a href="{{ route('articles.show', $comment->article) }}#comentarios" class="mt-1 inline-block text-xs text-indigo-600 hover:underline">{{ $comment->article->title }}</a>
                    </div>
                </div>
            @empty
                <p class="p-6 text-sm text-gray-500">Todavía no has recibido comentarios.</p>
            @endforelse
        </div>
    </section>
</x-app-layout>
