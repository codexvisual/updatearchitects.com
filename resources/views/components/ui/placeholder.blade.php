@props([
    'label' => null,
    'icon' => 'building',
    'ratio' => 'video',
])

@php
    $ratioClass = match ($ratio) {
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'wide' => 'aspect-[21/9]',
        default => 'aspect-video',
    };
@endphp

<div
    {{ $attributes->merge([
        'class' => "group relative w-full {$ratioClass} overflow-hidden rounded-lg bg-gradient-to-br from-stone-100 via-stone-50 to-stone-200 dark:from-stone-800 dark:via-stone-900 dark:to-stone-800",
    ]) }}
    role="img"
    @if($label) aria-label="{{ $label }}" @else aria-hidden="true" @endif
>
    <div class="absolute inset-0 opacity-[0.35] [background-image:radial-gradient(circle_at_1px_1px,theme(colors.stone.400)_1px,transparent_0)] [background-size:18px_18px] dark:opacity-[0.15]"></div>

    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-4 text-center">
        @if($icon === 'building')
            <svg class="h-8 w-8 text-stone-500 dark:text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M9 10h.01M15 10h.01M9 13h.01M15 13h.01M9 16h.01M15 16h.01" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @elseif($icon === 'photo')
            <svg class="h-8 w-8 text-stone-500 dark:text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                <rect x="3" y="4" width="18" height="16" rx="2"/>
                <circle cx="8.5" cy="9.5" r="1.5"/>
                <path d="m21 16-4.5-4.5L9 19" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @elseif($icon === 'avatar')
            <svg class="h-10 w-10 text-stone-500 dark:text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                <circle cx="12" cy="8" r="3.5"/>
                <path d="M4.5 20a7.5 7.5 0 0 1 15 0" stroke-linecap="round"/>
            </svg>
        @else
            <svg class="h-8 w-8 text-stone-500 dark:text-stone-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                <circle cx="12" cy="12" r="8.5"/>
                <path d="M12 8v8M8 12h8" stroke-linecap="round"/>
            </svg>
        @endif

        @if($label)
            <p class="text-caption font-medium tracking-wide text-stone-500 dark:text-stone-400">{{ $label }}</p>
        @endif
    </div>
</div>
