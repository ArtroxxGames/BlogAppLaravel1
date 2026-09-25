<x-blog-layout title="Categorías">
    <h1 class="text-3xl font-bold tracking-tight">Todas las categorías</h1>
    <p class="mt-2 text-gray-600">Explora los artículos por tema.</p>

    @if ($categories->isEmpty())
        <x-empty-state class="mt-8">Todavía no hay categorías.</x-empty-state>
    @else
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="group relative flex h-44 flex-col justify-end overflow-hidden rounded-2xl bg-gradient-to-br from-slate-700 to-indigo-800 p-6 text-white shadow-sm">
                    @if ($image = $category->imageUrl())
                        <img src="{{ $image }}" alt="" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-40 transition duration-300 group-hover:scale-105">
                    @endif
                    <div class="relative">
                        <h2 class="text-xl font-semibold">{{ $category->name }}</h2>
                        @if ($category->description)
                            <p class="mt-1 line-clamp-2 text-sm text-white/80">{{ $category->description }}</p>
                        @endif
                        <p class="mt-2 text-xs font-medium uppercase tracking-wide text-white/70">
                            {{ trans_choice(':count artículo|:count artículos', $category->articles_count) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-blog-layout>
