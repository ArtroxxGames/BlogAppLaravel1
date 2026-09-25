@props(['article'])

<article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 transition hover:-translate-y-0.5 hover:shadow-md">
    <a href="{{ route('articles.show', $article) }}" class="block aspect-[16/9] overflow-hidden bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500">
        @if ($cover = $article->coverUrl())
            <img src="{{ $cover }}" alt="" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center p-5">
                <span class="text-center text-2xl font-bold uppercase tracking-widest text-white/30">{{ $article->category->name }}</span>
            </div>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('categories.show', $article->category) }}" class="rounded-full bg-indigo-50 px-2.5 py-1 font-medium text-indigo-700 hover:bg-indigo-100">
                {{ $article->category->name }}
            </a>
            <span class="text-gray-500">{{ $article->readingTime() }} min de lectura</span>
        </div>

        <h3 class="mt-3 text-lg font-semibold leading-snug text-gray-900">
            <a href="{{ route('articles.show', $article) }}" class="hover:text-indigo-600">{{ $article->title }}</a>
        </h3>

        <p class="mt-2 line-clamp-3 flex-1 text-sm text-gray-600">{{ $article->excerpt }}</p>

        <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
            <a href="{{ route('authors.show', $article->author) }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-indigo-600">
                <x-avatar :user="$article->author" size="h-7 w-7 text-xs" />
                <span>{{ $article->author->name }}</span>
            </a>
            <div class="flex items-center gap-3 text-xs text-gray-500">
                @if ($article->comments_count)
                    <x-star-rating :value="$article->comments_avg_rating" />
                @endif
                <time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->translatedFormat('d M Y') }}</time>
            </div>
        </div>
    </div>
</article>
