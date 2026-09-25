<x-app-layout title="Comentarios">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Moderación de comentarios</h1>
    </x-slot>

    @if ($comments->isEmpty())
        <x-empty-state>No hay comentarios.</x-empty-state>
    @else
        <div class="divide-y divide-gray-100 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
            @foreach ($comments as $comment)
                <div class="flex items-start gap-4 p-5">
                    <x-avatar :user="$comment->author" size="h-9 w-9 text-xs" />
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                            <span class="font-medium">{{ $comment->author->name }}</span>
                            <x-star-rating :value="$comment->rating" />
                            <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 whitespace-pre-line text-sm text-gray-700">{{ $comment->body }}</p>
                        <a href="{{ route('articles.show', $comment->article) }}#comentarios" class="mt-1 inline-block text-xs text-indigo-600 hover:underline">{{ $comment->article->title }}</a>
                    </div>
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('¿Eliminar este comentario?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm font-medium text-red-600 hover:underline">Eliminar</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $comments->links() }}</div>
    @endif
</x-app-layout>
