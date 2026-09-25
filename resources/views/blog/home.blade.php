<x-blog-layout :title="$search !== '' ? 'Resultados para «'.$search.'»' : null"
               description="Artículos sobre programación, productividad y crecimiento profesional.">
    @if ($search === '')
        <section class="mb-12 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 px-6 py-14 text-white sm:px-12">
            <h1 class="max-w-2xl text-4xl font-bold tracking-tight sm:text-5xl">Ideas para crecer profesionalmente</h1>
            <p class="mt-4 max-w-2xl text-lg text-indigo-100">
                Artículos escritos por la comunidad sobre programación, productividad, búsqueda de empleo y éxito laboral.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('categories.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">Explorar categorías</a>
                <a href="{{ auth()->check() ? route('dashboard.articles.create') : route('register') }}" class="rounded-full px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/40 hover:bg-white/10">
                    Escribe tu artículo →
                </a>
            </div>
        </section>
    @else
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-bold">
                {{ trans_choice(':count resultado|:count resultados', $articles->total()) }} para «{{ $search }}»
            </h1>
            <a href="{{ route('home') }}" class="text-sm text-indigo-600 hover:underline">Limpiar búsqueda</a>
        </div>
    @endif

    @if ($articles->isEmpty())
        <x-empty-state>
            {{ $search !== '' ? 'No encontramos artículos que coincidan con tu búsqueda.' : 'Todavía no hay artículos publicados.' }}
        </x-empty-state>
    @else
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>

        <div class="mt-10">{{ $articles->links() }}</div>
    @endif
</x-blog-layout>
