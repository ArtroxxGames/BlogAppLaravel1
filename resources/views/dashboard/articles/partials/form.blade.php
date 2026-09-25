@csrf

@php
    $status = old('status', $article->published_at ? 'published' : 'draft');
    $publishedAt = old('published_at', $article->published_at?->format('Y-m-d\TH:i'));
@endphp

<div class="grid gap-8 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <div>
                <x-input-label for="title" value="Título" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full text-lg" :value="old('title', $article->title)" required autofocus maxlength="255" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                @if ($article->exists)
                    <p class="mt-1 text-xs text-gray-500">URL: {{ route('articles.show', $article) }}</p>
                @endif
            </div>

            <div class="mt-6">
                <x-input-label for="excerpt" value="Resumen" />
                <x-textarea id="excerpt" name="excerpt" rows="2" class="mt-1 block w-full" required maxlength="300">{{ old('excerpt', $article->excerpt) }}</x-textarea>
                <p class="mt-1 text-xs text-gray-500">Se muestra en los listados y en buscadores. Máximo 300 caracteres.</p>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="body" value="Contenido" />
                <x-textarea id="body" name="body" rows="20" class="mt-1 block w-full font-mono text-sm" required>{{ old('body', $article->body) }}</x-textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Admite <a href="https://www.markdownguide.org/basic-syntax/" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">Markdown</a>:
                    <code>## Subtítulo</code>, <code>**negrita**</code>, <code>- listas</code>, <code>[enlaces](https://…)</code>, bloques de código…
                </p>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200" x-data="{ status: '{{ $status }}' }">
            <h2 class="font-semibold">Publicación</h2>

            <div class="mt-4 space-y-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="radio" name="status" value="draft" x-model="status" class="text-indigo-600 focus:ring-indigo-500" @checked($status === 'draft')>
                    Borrador (solo tú lo ves)
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="radio" name="status" value="published" x-model="status" class="text-indigo-600 focus:ring-indigo-500" @checked($status === 'published')>
                    Publicado
                </label>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="mt-4" x-show="status === 'published'" x-cloak>
                <x-input-label for="published_at" value="Fecha de publicación" />
                <x-text-input id="published_at" name="published_at" type="datetime-local" class="mt-1 block w-full text-sm" :value="$publishedAt" />
                <p class="mt-1 text-xs text-gray-500">Déjalo vacío para publicar ahora, o elige una fecha futura para programarlo.</p>
                <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="category_id" value="Categoría" />
                <x-select id="category_id" name="category_id" class="mt-1 block w-full" required>
                    <option value="" disabled @selected(! old('category_id', $article->category_id))>Elige una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <h2 class="font-semibold">Imagen de portada</h2>

            @if ($cover = $article->coverUrl())
                <img src="{{ $cover }}" alt="Portada actual" class="mt-4 aspect-video w-full rounded-lg object-cover">
                <label class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                    <input type="hidden" name="remove_cover" value="0">
                    <input type="checkbox" name="remove_cover" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                    Quitar la portada actual
                </label>
            @endif

            <input id="cover" name="cover" type="file" accept="image/jpeg,image/png,image/webp"
                   class="mt-4 block w-full text-sm text-gray-600 file:me-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="mt-1 text-xs text-gray-500">JPG, PNG o WebP. Máximo 2 MB. Opcional.</p>
            <x-input-error :messages="$errors->get('cover')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">{{ $submit }}</x-primary-button>
    </div>
</div>
