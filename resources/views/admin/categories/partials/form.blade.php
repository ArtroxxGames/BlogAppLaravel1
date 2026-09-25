@csrf

<div class="max-w-2xl space-y-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus maxlength="40" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Descripción (opcional)" />
        <x-textarea id="description" name="description" rows="2" class="mt-1 block w-full" maxlength="255">{{ old('description', $category->description) }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="image" value="Imagen (opcional)" />
        @if ($image = $category->imageUrl())
            <img src="{{ $image }}" alt="Imagen actual" class="mt-2 h-32 rounded-lg object-cover">
        @endif
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"
               class="mt-2 block w-full text-sm text-gray-600 file:me-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>

    <div class="space-y-3">
        <label class="flex items-start gap-3">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" value="1" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500" @checked(old('is_visible', $category->is_visible))>
            <span class="text-sm">
                <span class="font-medium text-gray-900">Visible</span>
                <span class="block text-gray-500">Si la ocultas, sus artículos dejan de mostrarse en el blog.</span>
            </span>
        </label>
        <label class="flex items-start gap-3">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500" @checked(old('is_featured', $category->is_featured))>
            <span class="text-sm">
                <span class="font-medium text-gray-900">Destacada</span>
                <span class="block text-gray-500">Aparece en el menú principal del blog.</span>
            </span>
        </label>
    </div>

    <div class="flex justify-end">
        <x-primary-button>{{ $submit }}</x-primary-button>
    </div>
</div>
