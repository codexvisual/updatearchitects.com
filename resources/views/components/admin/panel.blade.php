@props([
    'title' => null,
    'subtitle' => null,
])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-stone-200 bg-white p-6 dark:border-stone-800 dark:bg-stone-900']) }}>
    @if($title)
        <header class="mb-5">
            <h2 class="font-display text-heading-md text-stone-900 dark:text-white">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-caption mt-1 text-stone-500 dark:text-stone-400">{{ $subtitle }}</p>
            @endif
        </header>
    @endif

    {{ $slot }}
</section>
