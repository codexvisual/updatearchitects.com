@props([
    'class' => '',
])

{{-- Blueprint-style facade elevation with crane and tree — decorative illustration --}}
<svg class="{{ $class }}" viewBox="0 0 640 340" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
    {{-- site boundary --}}
    <path d="M20 310H620" />

    {{-- main block with gable roof --}}
    <rect x="120" y="100" width="200" height="210" />
    <path d="M120 100L220 40L320 100" />

    {{-- right wing --}}
    <rect x="320" y="150" width="180" height="160" />

    {{-- windows --}}
    <path d="M150 140h40M150 190h40M150 240h40M230 140h40M230 190h40M230 240h40" />
    <path d="M350 190h40M400 190h40M450 190h30M350 245h40M400 245h40M450 245h30" />

    {{-- entrance --}}
    <path d="M200 310V255h40v55" stroke="#c7432c" stroke-width="4" />

    {{-- tree --}}
    <circle cx="65" cy="260" r="26" />
    <path d="M65 286V310" />

    {{-- crane --}}
    <path d="M555 30V310M555 30L620 65M590 52V92" />

    {{-- dimension line --}}
    <path d="M120 325H300" stroke-dasharray="6 6" />
    <path d="M120 318V332M300 318V332" />
</svg>
