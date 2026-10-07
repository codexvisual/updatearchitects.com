@props([
    'title' => '',
    'description' => '',
    'canonicalUrl' => null,
    'ogImage' => null,
    'schema' => [],
    'noIndex' => false,
    'noFollow' => false,
    'header' => null,
    'footer' => null,
])
@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="{{ $noIndex ? 'noindex' : 'index' }}, {{ $noFollow ? 'nofollow' : 'follow' }}">

    <title>{{ $title ?: config('app.name') }}</title>
    <meta name="description" content="{{ $description ?: Setting::getValue('seo.default_description', app()->getLocale()) }}">

    {{-- Canonical URL --}}
    @if($canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif

    {{-- Open Graph --}}
    @php
        $socialImage = $ogImage ?: Setting::getValue('seo.og_image', app()->getLocale());
        $socialImageIsHero = false;

        if (! $socialImage && file_exists(public_path('images/optimized/hero-1440.jpg'))) {
            $socialImage = asset('images/optimized/hero-1440.jpg');
            $socialImageIsHero = true;
        }

        if ($socialImage && ! str_contains($socialImage, '://')) {
            $socialImage = url($socialImage);
        }
    @endphp

    @if($socialImage)
        <meta property="og:image" content="{{ $socialImage }}">
        @if($socialImageIsHero)
            <meta property="og:image:width" content="1440">
            <meta property="og:image:height" content="1080">
        @endif
    @endif
    <meta property="og:url" content="{{ $canonicalUrl ?? request()->url() }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?: config('app.name') }}">
    <meta name="twitter:description" content="{{ $description ?: Setting::getValue('seo.default_description', app()->getLocale()) }}">
    @if($socialImage)
        <meta name="twitter:image" content="{{ $socialImage }}">
    @endif

    {{-- Schema.org JSON-LD --}}
    @if(!empty($schema))
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS) !!}</script>
    @endif

    {{-- Favicon & App Icons --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#1c1917">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Noto+Sans+Bengali:wght@400;500;600;700&family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inline critical CSS for above-the-fold --}}
    <style>
        /* Critical inline styles to prevent FOUC */
        .skip-link { position: absolute; top: -100%; left: 50%; transform: translateX(-50%); padding: 0.75rem 1.5rem; background: hsl(var(--color-primary)); color: hsl(var(--color-primary-foreground)); border-radius: 0.375rem; z-index: 9999; font-weight: 500; }
        .skip-link:focus { top: 1rem; }
    </style>
</head>
<body class="font-sans antialiased">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    {{-- Header --}}
    <header class="sticky top-0 z-40 w-full border-b border-stone-200 bg-white/95 backdrop-blur-sm dark:border-stone-800 dark:bg-stone-950/95" role="banner">
        {{ $header ?? view('components.layout.header') }}
    </header>
    {{-- Main Content --}}
    <main id="main-content" class="min-h-[calc(100vh-200px)]" role="main">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-950" role="contentinfo">
        {{ $footer ?? view('components.layout.footer') }}
    </footer>

    {{-- Cookie Consent Banner --}}
    @if(!request()->hasCookie('cookie_consent'))
        <div id="cookie-consent" class="fixed bottom-0 left-0 right-0 z-50 border-t border-stone-200 bg-white shadow-elevated dark:border-stone-800 dark:bg-stone-950" role="dialog" aria-label="Cookie consent">
            <div class="container py-4 md:flex md:items-center md:justify-between gap-4">
                <p class="text-body-sm text-stone-600 dark:text-stone-400">
                    We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.
                </p>
                <div class="flex gap-3">
                    <button id="cookie-accept" class="btn-primary btn-sm">Accept</button>
                    <button id="cookie-decline" class="btn-ghost btn-sm">Decline</button>
                    <a href="{{ route('privacy') }}" class="btn-link btn-sm text-sm">Learn more</a>
                </div>
            </div>
        </div>
    @endif

    {{-- Scroll progress line --}}
    <div id="scroll-progress" class="fixed top-0 left-0 z-[60] h-[3px] w-0 bg-accent-600 transition-[width] duration-100" aria-hidden="true"></div>

    {{-- WhatsApp chat --}}
    <x-whatsapp-button />

    {{-- Back to top --}}
    <button
        type="button"
        x-data="{ visible: false }"
        x-cloak
        x-init="
            const onScroll = () => { visible = window.scrollY > 600; };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        "
        x-show="visible"
        x-transition.opacity.duration.300ms
        @click="window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })"
        class="fixed bottom-24 right-6 z-40 inline-flex h-12 w-12 items-center justify-center rounded-full bg-stone-900 text-white shadow-elevated transition-colors hover:bg-accent-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 dark:bg-white dark:text-stone-900 dark:hover:bg-accent-500"
        aria-label="Back to top"
    >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    {{-- Cookie consent behaviour --}}
    <script defer>
        document.addEventListener('DOMContentLoaded', () => {
            const banner = document.getElementById('cookie-consent');
            if (!banner) return;

            const dismiss = (value) => {
                document.cookie = `cookie_consent=${value};path=/;max-age=${60 * 60 * 24 * 365};samesite=lax`;
                banner.remove();
            };

            document.getElementById('cookie-accept')?.addEventListener('click', () => dismiss('accepted'));
            document.getElementById('cookie-decline')?.addEventListener('click', () => dismiss('declined'));
        });
    </script>

    @stack('scripts')
</body>
</html>


