<x-blog-layout :title="$article->title" :description="$article->excerpt">
    <article class="mx-auto max-w-3xl">
        @unless ($article->isPublished())
            <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                {{ $article->isScheduled() ? 'Este artículo está programado para el '.$article->published_at->translatedFormat('d \d\e F \a \l\a\s H:i').'.' : 'Este artículo es un borrador.' }}
                Solo tú puedes verlo.
            </div>
        @endunless

        <header>
            <a href="{{ route('categories.show', $article->category) }}" class="text-sm font-semibold uppercase tracking-wide text-indigo-600 hover:text-indigo-500">
                {{ $article->category->name }}
            </a>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">{{ $article->title }}</h1>
            <p class="mt-4 text-lg text-gray-600">{{ $article->excerpt }}</p>

            <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-y border-gray-200 py-4">
                <a href="{{ route('authors.show', $article->author) }}" class="flex items-center gap-3">
                    <x-avatar :user="$article->author" size="h-11 w-11 text-sm" />
                    <div>
                        <p class="font-medium text-gray-900 hover:text-indigo-600">{{ $article->author->name }}</p>
                        <p class="text-sm text-gray-500">
                            @if ($article->published_at)
                                <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->translatedFormat('d \d\e F, Y') }}</time> ·
                            @endif
                            {{ $article->readingTime() }} min de lectura
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-4">
                    @if ($article->comments_count)
                        <span class="flex items-center gap-2 text-sm text-gray-600">
                            <x-star-rating :value="$article->comments_avg_rating" />
                            {{ number_format($article->comments_avg_rating, 1) }}
                            ({{ $article->comments_count }})
                        </span>
                    @endif

                    @can('update', $article)
                        <a href="{{ route('dashboard.articles.edit', $article) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Editar</a>
                    @endcan
                </div>
            </div>
        </header>

        @if ($cover = $article->coverUrl())
            <img src="{{ $cover }}" alt="" class="mt-8 w-full rounded-2xl object-cover">
        @endif

        <div class="prose prose-lg prose-indigo mt-10 max-w-none">
            {{ $article->bodyHtml() }}
        </div>
    </article>

    <section id="comentarios" class="mx-auto mt-16 max-w-3xl scroll-mt-24">
        <h2 class="text-2xl font-bold">Comentarios <span class="text-gray-400">({{ $article->comments_count }})</span></h2>

        <div class="mt-6">
            @guest
                <div class="rounded-2xl bg-white p-6 text-center shadow-sm ring-1 ring-gray-200">
                    <p class="text-gray-600">
                        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:underline">Inicia sesión</a>
                        o <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:underline">crea una cuenta</a>
                        para comentar y valorar este artículo.
                    </p>
                </div>
            @else
                @if ($canComment)
                    @include('blog.articles.partials.comment-form')
                @elseif ($article->isPublished())
                    <p class="rounded-lg bg-gray-100 px-4 py-3 text-sm text-gray-600">Ya has dejado tu valoración en este artículo. ¡Gracias!</p>
                @endif
            @endguest
        </div>

        <div class="mt-8 space-y-4">
            @forelse ($comments as $comment)
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                    <div class="flex items-start justify-between gap-4">
                        <a href="{{ route('authors.show', $comment->author) }}" class="flex items-center gap-3">
                            <x-avatar :user="$comment->author" size="h-9 w-9 text-xs" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $comment->author->name }}</p>
                                <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                        <div class="flex items-center gap-3">
                            <x-star-rating :value="$comment->rating" />
                            @can('delete', $comment)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('¿Eliminar este comentario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Eliminar</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <p class="mt-3 whitespace-pre-line text-gray-700">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="text-gray-500">Aún no hay comentarios. ¡Sé el primero en opinar!</p>
            @endforelse

            <div>{{ $comments->fragment('comentarios')->links() }}</div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="mt-20">
            <h2 class="text-2xl font-bold">Más en {{ $article->category->name }}</h2>
            <div class="mt-6 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $item)
                    <x-article-card :article="$item" />
                @endforeach
            </div>
        </section>
    @endif
</x-blog-layout>
