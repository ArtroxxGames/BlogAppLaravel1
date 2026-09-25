<footer class="mt-20 border-t border-gray-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 py-8 text-sm text-gray-500 sm:flex-row sm:px-6 lg:px-8">
        <p>&copy; {{ now()->year }} {{ config('app.name') }}. Hecho con Laravel.</p>
        <nav class="flex gap-6">
            <a href="{{ route('home') }}" class="hover:text-gray-900">Inicio</a>
            <a href="{{ route('categories.index') }}" class="hover:text-gray-900">Categorías</a>
            @guest
                <a href="{{ route('register') }}" class="hover:text-gray-900">Escribe con nosotros</a>
            @endguest
        </nav>
    </div>
</footer>
