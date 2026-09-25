<x-blog-layout :title="$category->name" :description="$category->description">
    <div class="mb-10">
        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Categoría</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight sm:text-4xl">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="mt-3 max-w-2xl text-gray-600">{{ $category->description }}</p>
        @endif
    </div>

    @if ($articles->isEmpty())
        <x-empty-state>Todavía no hay artículos en esta categoría.</x-empty-state>
    @else
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>

        <div class="mt-10">{{ $articles->links() }}</div>
    @endif
</x-blog-layout>
