@props([
    'variant' => 'primary',
])

@php
    $badge = new \App\View\Components\UI\Badge(variant: $variant);
    $classes = $badge->classes();
@endphp

<span
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</span>