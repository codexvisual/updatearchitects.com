@props([
    'class' => '',
])

{{-- Skyline with a couple of human figures — decorative architectural illustration --}}
<svg class="{{ $class }}" viewBox="0 0 1440 200" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMax slice">
    <g fill="currentColor">
        <rect x="0" y="120" width="70" height="80" rx="2"/>
        <rect x="80" y="80" width="60" height="120" rx="2"/>
        <rect x="150" y="140" width="80" height="60" rx="2"/>
        <rect x="240" y="60" width="50" height="140" rx="2"/>
        <rect x="300" y="110" width="90" height="90" rx="2"/>
        <rect x="400" y="90" width="70" height="110" rx="2"/>
        <rect x="480" y="140" width="60" height="60" rx="2"/>
        <rect x="1240" y="50" width="55" height="150" rx="2"/>
        <rect x="1305" y="100" width="80" height="100" rx="2"/>
        <rect x="1400" y="130" width="40" height="70" rx="2"/>
        <rect x="1330" y="70" width="2" height="30" rx="1" transform="translate(0 -18)"/>
        {{-- Arched portal / gate --}}
        <path d="M620 200 V120 a40 40 0 0 1 80 0 V200 Z" />
        {{-- Centre-right complex --}}
        <rect x="720" y="100" width="120" height="100" rx="2"/>
        <rect x="740" y="70" width="60" height="30" rx="2"/>
        <rect x="860" y="130" width="80" height="70" rx="2"/>
        {{-- Crane --}}
        <rect x="1060" y="40" width="4" height="160"/>
        <rect x="1063" y="40" width="120" height="4"/>
        <rect x="1175" y="44" width="3" height="18"/>
        <rect x="1054" y="196" width="16" height="4"/>
    </g>

    {{-- Human figures --}}
    <g stroke="currentColor" stroke-width="5" stroke-linecap="round">
        <circle cx="665" cy="150" r="7" fill="currentColor" stroke="none"/>
        <path d="M665 158 v22 m0 -12 l-10 12 m10 -12 l10 12 m-10 22 l-8 14 m8 -14 l8 14"/>
        <circle cx="1005" cy="150" r="7" fill="currentColor" stroke="none"/>
        <path d="M1005 158 v22 m0 -12 l-10 12 m10 -12 l10 12 m-10 22 l-8 14 m8 -14 l8 14"/>
    </g>
</svg>
