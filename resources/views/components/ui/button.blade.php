@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'href' => null,
])

@php
    $btn = new \App\View\Components\UI\Button(variant: $variant, size: $size, type: $type, disabled: $disabled, href: $href);
    $classes = $btn->classes();
    $isLink = $btn->isLink();
@endphp

@if($isLink)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes, 'role' => 'button']) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        {{ $disabled ? 'disabled' : '' }}
    >
        {{ $slot }}
    </button>
@endif