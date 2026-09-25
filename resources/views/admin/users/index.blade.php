<x-app-layout title="Usuarios">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
        <form method="GET" role="search">
            <input type="search" name="q" value="{{ $search }}" placeholder="Nombre o correo…" aria-label="Buscar usuarios"
                   class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </form>
    </x-slot>

    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th scope="col" class="px-5 py-3">Usuario</th>
                    <th scope="col" class="px-5 py-3">Artículos</th>
                    <th scope="col" class="px-5 py-3">Comentarios</th>
                    <th scope="col" class="px-5 py-3">Registro</th>
                    <th scope="col" class="px-5 py-3">Rol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <a href="{{ route('authors.show', $user) }}" class="flex items-center gap-3">
                                <x-avatar :user="$user" size="h-9 w-9 text-xs" />
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </a>
                        </td>
                        <td class="px-5 py-4 text-gray-600">{{ $user->articles_count }}</td>
                        <td class="px-5 py-4 text-gray-600">{{ $user->comments_count }}</td>
                        <td class="px-5 py-4 text-gray-600">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="flex items-center gap-3">
                                @csrf
                                @method('PATCH')
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-xs font-medium',
                                    'bg-indigo-50 text-indigo-700' => $user->is_admin,
                                    'bg-gray-100 text-gray-600' => ! $user->is_admin,
                                ])>{{ $user->is_admin ? 'Administrador' : 'Autor' }}</span>
                                @unless (Auth::user()->is($user))
                                    <button class="text-xs text-indigo-600 hover:underline">{{ $user->is_admin ? 'Quitar admin' : 'Hacer admin' }}</button>
                                @endunless
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</x-app-layout>
