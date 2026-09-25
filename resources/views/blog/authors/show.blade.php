<x-blog-layout :title="$author->name" :description="$author->bio">
    <section class="mb-12 flex flex-col items-center gap-6 rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-gray-200 sm:flex-row sm:text-left">
        <x-avatar :user="$author" size="h-24 w-24 text-2xl" />
        <div class="flex-1">
            <h1 class="text-2xl font-bold">{{ $author->name }}</h1>
            @if ($author->profession)
                <p class="text-indigo-600">{{ $author->profession }}</p>
            @endif
            @if ($author->bio)
                <p class="mt-3 max-w-2xl text-gray-600">{{ $author->bio }}</p>
            @endif
            @if ($links = $author->socialLinks())
                <div class="mt-4 flex flex-wrap justify-center gap-3 sm:justify-start">
                    @foreach ($links as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer nofollow" class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700 hover:bg-gray-200">{{ $network }}</a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="text-center">
            <p class="text-3xl font-bold">{{ $articles->total() }}</p>
            <p class="text-sm text-gray-500">{{ trans_choice('artículo publicado|artículos publicados', $articles->total()) }}</p>
        </div>
    </section>

    @if ($articles->isEmpty())
        <x-empty-state>{{ $author->name }} todavía no ha publicado artículos.</x-empty-state>
    @else
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>

        <div class="mt-10">{{ $articles->links() }}</div>
    @endif
</x-blog-layout>
