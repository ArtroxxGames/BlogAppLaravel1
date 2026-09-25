<x-app-layout title="Categorías">
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Categorías</h1>
        <a href="{{ route('admin.categories.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Nueva categoría</a>
    </x-slot>

    @if ($categories->isEmpty())
        <x-empty-state>Todavía no hay categorías.</x-empty-state>
    @else
        <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th scope="col" class="px-5 py-3">Nombre</th>
                        <th scope="col" class="px-5 py-3">Artículos</th>
                        <th scope="col" class="px-5 py-3">Visible</th>
                        <th scope="col" class="px-5 py-3">Destacada</th>
                        <th scope="col" class="px-5 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($image = $category->imageUrl())
                                        <img src="{{ $image }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <span class="h-10 w-10 rounded-lg bg-gradient-to-br from-slate-600 to-indigo-700"></span>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500">/{{ $category->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $category->articles_count }}</td>
                            <td class="px-5 py-4">{{ $category->is_visible ? 'Sí' : 'No' }}</td>
                            <td class="px-5 py-4">{{ $category->is_featured ? 'Sí' : 'No' }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="font-medium text-indigo-600 hover:underline">Editar</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="ms-4 inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-medium text-red-600 hover:underline disabled:cursor-not-allowed disabled:opacity-40" @disabled($category->articles_count > 0)
                                            title="{{ $category->articles_count ? 'Tiene artículos: no se puede eliminar' : '' }}">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $categories->links() }}</div>
    @endif
</x-app-layout>
