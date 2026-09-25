@props(['value'])

@php($rounded = (int) round((float) $value))

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5']) }} title="{{ number_format((float) $value, 1) }} de 5">
    <span class="sr-only">{{ number_format((float) $value, 1) }} de 5 estrellas</span>
    @for ($i = 1; $i <= 5; $i++)
        <svg aria-hidden="true" class="h-4 w-4 {{ $i <= $rounded ? 'text-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.07 3.29a1 1 0 0 0 .95.69h3.46c.97 0 1.37 1.24.59 1.81l-2.8 2.03a1 1 0 0 0-.36 1.12l1.07 3.29c.3.92-.76 1.69-1.54 1.12l-2.8-2.03a1 1 0 0 0-1.18 0l-2.8 2.03c-.78.57-1.83-.2-1.54-1.12l1.07-3.29a1 1 0 0 0-.36-1.12L2.98 8.72c-.78-.57-.38-1.81.59-1.81h3.46a1 1 0 0 0 .95-.69l1.07-3.29Z"/>
        </svg>
    @endfor
</span>
