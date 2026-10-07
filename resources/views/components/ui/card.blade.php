@props([
    'variant' => 'default',
    'href' => null,
])

@php
    $card = new \App\View\Components\UI\Card(variant: $variant, href: $href);
    $classes = $card->classes();
    $isLink = $card->isLink();
@endphp

@if($isLink)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </a>
@else
    <div
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </div>
@endif