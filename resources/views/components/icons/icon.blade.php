@props([
    'name' => 'default',
    'class' => 'h-6 w-6',
])

@php
    $paths = [
        'architectural-design' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5',
        'structural-design' => 'M4 4h16M4 20h16M8 4v16M16 4v16M8 12h8',
        'geotechnical-engineering' => 'm12 2 10 5-10 5L2 7l10-5Zm-10 10 10 5 10-5M2 17l10 5 10-5',
        'construction-consultancy' => 'M14 4l6 6-2 2-6-6 2-2ZM12 6 3.5 14.5a2.12 2.12 0 0 0 3 3L15 9',
        'interior-design' => 'M5 11V8a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v3M3 13a2 2 0 0 1 4 0v1h10v-1a2 2 0 0 1 4 0v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4Z',
        'electrical-design' => 'M13 2 3 14h8l-1 8 10-12h-8l1-8Z',
        'rajuk-approval' => 'M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9l-6-6Zm-4 10 2 2 4-4ZM14 3v6h6',
        'bank-loan-sheet' => 'M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9l-6-6Zm0 0v6h6M9 13h6M9 17h6',
        'layers' => 'm12 2 10 5-10 5L2 7l10-5Zm-10 10 10 5 10-5M2 17l10 5 10-5',
        'shield-check' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016Z',
        'map-pin' => 'M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z',
        'bulb' => 'M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3v1a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-1c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2Z',
        'users' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
        'eye' => 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
        'default' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5',
    ];

    $path = $paths[$name] ?? $paths['default'];
@endphp

<svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="{{ $path }}" />
</svg>
