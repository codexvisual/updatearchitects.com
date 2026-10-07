@props([
    'name' => '',
    'photo' => null,
    'size' => 'md',
])

@php
    // px is the rendered square: it becomes width/height so the circle can be
    // laid out before the photograph arrives, and sizes so the browser knows
    // how many pixels of it actually need downloading.
    $metrics = [
        'sm' => ['class' => 'h-10 w-10 text-sm', 'px' => 40],
        'md' => ['class' => 'h-16 w-16 text-lg', 'px' => 64],
        'lg' => ['class' => 'h-24 w-24 text-2xl', 'px' => 96],
        'xl' => ['class' => 'h-32 w-32 text-3xl', 'px' => 128],
    ];
    $metric = $metrics[$size] ?? $metrics['md'];
    $sizeClass = $metric['class'];
    $sizePx = $metric['px'];

    // Deterministic palette pick from name so the same person always gets the same colour.
    $palette = [
        ['from-accent-500', 'to-accent-700'],
        ['from-emerald-500', 'to-emerald-700'],
        ['from-sky-500', 'to-sky-700'],
        ['from-violet-500', 'to-violet-700'],
        ['from-amber-500', 'to-amber-700'],
        ['from-rose-500', 'to-rose-700'],
        ['from-teal-500', 'to-teal-700'],
        ['from-indigo-500', 'to-indigo-700'],
    ];

    $colors = $palette[abs(crc32($name)) % count($palette)];

    // Initials: first letters of up to two words, skipping titles.
    $skip = ['engr.', 'arch.', 'dr.', 'mr.', 'mrs.', 'ms.', 'prof.'];
    $words = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach ($words as $word) {
        if (in_array(strtolower($word), $skip, true)) {
            continue;
        }
        $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        if (mb_strlen($initials) >= 2) {
            break;
        }
    }
    if ($initials === '') {
        $initials = 'U';
    }
@endphp

@if($photo)
    @php
        // Avatars are never displayed beyond 128px, so anything wider than the
        // 480px thumbnail is a download the browser should not be offered
        // unless it is genuinely short of candidates.
        $src = $photo->getAvailableUrl(['thumbnail', 'medium']);
        $srcset = $photo->responsiveSrcset(['thumbnail', 'medium']);
    @endphp
    <img
        src="{{ $src }}"
        @if($srcset) srcset="{{ $srcset }}" sizes="{{ $sizePx }}px" @endif
        width="{{ $sizePx }}"
        height="{{ $sizePx }}"
        alt="{{ $name }}"
        {{ $attributes->merge(['class' => "rounded-full object-cover shrink-0 ring-2 ring-accent-200 dark:ring-accent-900 $sizeClass"]) }}
        loading="lazy"
        decoding="async"
    >
@else
    <span
        role="img"
        aria-label="{{ $name }}"
        {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-full bg-gradient-to-br $colors[0] $colors[1] text-white font-semibold shrink-0 ring-2 ring-accent-200/50 dark:ring-accent-900/50 select-none $sizeClass"]) }}
    >
        {{ $initials }}
    </span>
@endif
