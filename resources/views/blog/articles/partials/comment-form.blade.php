<form method="POST" action="{{ route('comments.store', $article) }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200"
      x-data="{ rating: {{ (int) old('rating', 5) }}, hover: 0 }">
    @csrf

    <fieldset>
        <legend class="text-sm font-medium text-gray-700">Tu valoración</legend>
        <div class="mt-2 flex gap-1" @mouseleave="hover = 0">
            @for ($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer" @mouseenter="hover = {{ $i }}">
                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only" x-model.number="rating" @checked(old('rating', 5) == $i)>
                    <span class="sr-only">{{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}</span>
                    <svg aria-hidden="true" class="h-7 w-7 transition" :class="(hover || rating) >= {{ $i }} ? 'text-amber-400' : 'text-gray-300'" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.07 3.29a1 1 0 0 0 .95.69h3.46c.97 0 1.37 1.24.59 1.81l-2.8 2.03a1 1 0 0 0-.36 1.12l1.07 3.29c.3.92-.76 1.69-1.54 1.12l-2.8-2.03a1 1 0 0 0-1.18 0l-2.8 2.03c-.78.57-1.83-.2-1.54-1.12l1.07-3.29a1 1 0 0 0-.36-1.12L2.98 8.72c-.78-.57-.38-1.81.59-1.81h3.46a1 1 0 0 0 .95-.69l1.07-3.29Z"/>
                    </svg>
                </label>
            @endfor
        </div>
        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
    </fieldset>

    <div class="mt-4">
        <x-input-label for="body" value="Comentario" />
        <x-textarea id="body" name="body" rows="4" class="mt-1 block w-full" maxlength="1000" required placeholder="¿Qué te pareció el artículo?">{{ old('body') }}</x-textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <div class="mt-4 flex justify-end">
        <x-primary-button>Publicar comentario</x-primary-button>
    </div>
</form>
