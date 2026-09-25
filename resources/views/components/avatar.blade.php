@props(['user', 'size' => 'h-10 w-10 text-sm'])

@if ($url = $user->avatarUrl())
    <img src="{{ $url }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => "$size shrink-0 rounded-full object-cover"]) }}>
@else
    <span aria-hidden="true" {{ $attributes->merge(['class' => "$size inline-flex shrink-0 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-700"]) }}>
        {{ $user->initials() }}
    </span>
@endif
