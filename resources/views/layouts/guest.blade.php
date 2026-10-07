<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- Console pages must never be indexed or followed. --}}
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Same typefaces as the public site, so the console matches the brand. --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
        <meta name="theme-color" content="#1c1917">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen bg-stone-50 font-sans text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
        {{--
            flex on phones so the form column stretches to the full viewport and
            pins the footer down; grid on desktop for the two-panel split.
        --}}
        <div class="flex min-h-screen flex-col lg:grid lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)]">

            {{-- Brand panel — desktop only; the compact header below carries the identity on small screens. --}}
            <aside class="relative hidden overflow-hidden bg-stone-950 px-10 py-12 text-white lg:flex lg:flex-col lg:justify-between xl:px-16">
                {{-- Drafting-grid backdrop --}}
                <div class="pointer-events-none absolute inset-0 opacity-[0.16] [background-image:linear-gradient(to_right,#44403c_1px,transparent_1px),linear-gradient(to_bottom,#44403c_1px,transparent_1px)] [background-size:56px_56px]"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-24 h-80 w-80 rounded-full bg-accent-600/20 blur-3xl"></div>

                <div class="relative">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-4 rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-4 focus-visible:ring-offset-stone-950">
                        {{-- Cropped mark on a white chip; the full lockup is unreadable at this size. --}}
                        <span class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white p-2 shadow-soft">
                            <img src="{{ asset('logo-mark.jpg') }}" alt="" class="h-7 w-auto" width="360" height="186" decoding="async">
                        </span>
                        <span class="font-display text-lg leading-tight sm:text-xl">
                            Update Architects<br>
                            <span class="text-stone-400">&amp; Engineering</span>
                        </span>
                    </a>
                </div>

                <div class="relative">
                    <span class="block h-px w-16 bg-accent-500" aria-hidden="true"></span>
                    <h1 class="font-display mt-6 text-display-sm text-white">The admin console</h1>
                    <p class="mt-4 max-w-sm text-body-sm leading-relaxed text-stone-400">
                        Sign in to manage projects, insights, team profiles and site settings.
                    </p>
                </div>

                <p class="relative text-caption uppercase tracking-[0.18em] text-stone-400">
                    Architecture · Engineering · Construction · Interior
                </p>
            </aside>

            {{-- Form side --}}
            <main class="flex flex-col">
                {{-- Compact identity row — small screens only --}}
                <div class="border-b border-stone-200 bg-white px-4 py-4 dark:border-stone-800 dark:bg-stone-900 lg:hidden">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2">
                        <span class="inline-flex shrink-0 items-center justify-center rounded-lg bg-stone-100 p-1.5 dark:bg-stone-800">
                            <img src="{{ asset('logo-mark.jpg') }}" alt="" class="h-6 w-auto" width="360" height="186" decoding="async">
                        </span>
                        <span class="font-display text-base leading-tight">
                            Update Architects<br>
                            <span class="text-stone-500 dark:text-stone-400">&amp; Engineering</span>
                        </span>
                    </a>
                </div>

                <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-8">
                    <div class="w-full max-w-md rounded-xl border border-stone-200 bg-white p-6 shadow-elevated sm:p-8 dark:border-stone-800 dark:bg-stone-900">
                        {{ $slot }}
                    </div>
                </div>

                <div class="px-4 pb-6 text-center">
                    <a href="{{ url('/') }}" class="inline-block rounded py-2 text-caption text-stone-500 transition-colors hover:text-accent-600 dark:text-stone-400 dark:hover:text-accent-400">
                        &larr; Back to the website
                    </a>
                </div>
            </main>
        </div>
    </body>
</html>
