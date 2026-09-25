<header x-data="{ open: false }" class="sticky top-0 z-30 border-b border-gray-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-8">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 whitespace-nowrap font-bold text-gray-900">
                <x-application-logo class="h-8 w-8" />
                <span>{{ config('app.name') }}</span>
            </a>

            <nav class="hidden items-center gap-5 whitespace-nowrap text-sm font-medium text-gray-600 lg:flex" aria-label="Principal">
                <a href="{{ route('home') }}" @class(['hover:text-gray-900', 'text-gray-900' => request()->routeIs('home')])>Inicio</a>
                @foreach ($featuredCategories as $category)
                    <a href="{{ route('categories.show', $category) }}" @class(['hover:text-gray-900', 'text-gray-900' => request()->is('categorias/'.$category->slug)])>{{ $category->name }}</a>
                @endforeach
                <a href="{{ route('categories.index') }}" @class(['hover:text-gray-900', 'text-gray-900' => request()->routeIs('categories.index')])>Todas las categorías</a>
            </nav>
        </div>

        <div class="flex shrink-0 items-center gap-3">
            <form action="{{ route('home') }}" method="GET" role="search" class="hidden 2xl:block">
                <label for="header-search" class="sr-only">Buscar artículos</label>
                <input id="header-search" type="search" name="q" value="{{ request()->routeIs('home') ? request('q') : '' }}" placeholder="Buscar artículos…"
                       class="w-48 rounded-full border-gray-200 bg-gray-50 px-4 py-1.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </form>

            @auth
                <a href="{{ route('dashboard.articles.create') }}" class="hidden rounded-full bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500 sm:inline-block">
                    Escribir
                </a>

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" aria-label="Menú de usuario">
                            <x-avatar :user="Auth::user()" size="h-9 w-9 text-sm" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-gray-100 px-4 py-2">
                            <p class="truncate text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('dashboard')">Panel</x-dropdown-link>
                        <x-dropdown-link :href="route('dashboard.articles.index')">Mis artículos</x-dropdown-link>
                        <x-dropdown-link :href="route('authors.show', Auth::user())">Mi perfil público</x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">Configuración</x-dropdown-link>

                        @can('admin')
                            <div class="border-t border-gray-100 px-4 pb-1 pt-2 text-xs font-semibold uppercase tracking-wide text-gray-400">Administración</div>
                            <x-dropdown-link :href="route('admin.categories.index')">Categorías</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.comments.index')">Comentarios</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.users.index')">Usuarios</x-dropdown-link>
                        @endcan

                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="hidden whitespace-nowrap text-sm font-medium text-gray-600 hover:text-gray-900 sm:inline">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="hidden whitespace-nowrap rounded-full bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500 sm:inline">Crear cuenta</a>
            @endauth

            <button @click="open = ! open" class="rounded-md p-2 text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="Abrir menú" :aria-expanded="open">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path x-show="! open" stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-gray-100 px-4 py-4 lg:hidden">
        <form action="{{ route('home') }}" method="GET" role="search" class="mb-3">
            <input type="search" name="q" placeholder="Buscar artículos…" aria-label="Buscar artículos"
                   class="w-full rounded-full border-gray-200 bg-gray-50 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </form>
        <nav class="grid gap-1 text-sm font-medium text-gray-700">
            <a href="{{ route('home') }}" class="rounded px-2 py-1.5 hover:bg-gray-50">Inicio</a>
            @foreach ($featuredCategories as $category)
                <a href="{{ route('categories.show', $category) }}" class="rounded px-2 py-1.5 hover:bg-gray-50">{{ $category->name }}</a>
            @endforeach
            <a href="{{ route('categories.index') }}" class="rounded px-2 py-1.5 hover:bg-gray-50">Todas las categorías</a>
            @auth
                <a href="{{ route('dashboard.articles.create') }}" class="rounded px-2 py-1.5 text-indigo-600 hover:bg-gray-50">Escribir un artículo</a>
            @else
                <a href="{{ route('login') }}" class="rounded px-2 py-1.5 hover:bg-gray-50">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="rounded px-2 py-1.5 text-indigo-600 hover:bg-gray-50">Crear cuenta</a>
            @endauth
        </nav>
    </div>
</header>
