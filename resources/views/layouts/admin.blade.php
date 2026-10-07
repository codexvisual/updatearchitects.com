@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }} Admin</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
<a href="#admin-content" class="skip-link">Skip to main content</a>

<div class="min-h-screen lg:flex">
    {{-- Sidebar --}}
    <aside
        id="admin-sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 shrink-0 -translate-x-full border-r border-stone-800 bg-stone-950 transition-transform duration-200 lg:static lg:translate-x-0"
        aria-label="Admin navigation"
    >
        <div class="flex h-full flex-col">
            <div class="flex items-center gap-3 border-b border-stone-800 px-6 py-5">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-accent-600 text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5"/>
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold text-white truncate">{{ config('app.name') }}</span>
                    <span class="block text-caption uppercase tracking-wider text-stone-400">Admin</span>
                </span>
                <button
                    type="button"
                    class="ml-auto btn-ghost p-1.5 lg:hidden"
                    @click="document.getElementById('admin-sidebar').classList.add('-translate-x-full'); document.getElementById('admin-overlay').classList.add('hidden')"
                    aria-label="Close navigation"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6" aria-label="Sections">
                @php
                    $navigation = [
                        [
                            'label' => 'Overview',
                            'items' => [
                                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'grid'],
                            ],
                        ],
                        [
                            'label' => 'Content',
                            'items' => [
                                ['route' => 'admin.projects.index', 'label' => 'Projects', 'icon' => 'building'],
                                ['route' => 'admin.project-categories.index', 'label' => 'Project Categories', 'icon' => 'tag'],
                                ['route' => 'admin.services.index', 'label' => 'Services', 'icon' => 'layers'],
                                ['route' => 'admin.service-categories.index', 'label' => 'Service Categories', 'icon' => 'folder'],
                                ['route' => 'admin.hero-slides.index', 'label' => 'Hero Slides', 'icon' => 'image'],
                                ['route' => 'admin.team.index', 'label' => 'Team', 'icon' => 'users'],
                                ['route' => 'admin.offices.index', 'label' => 'Offices', 'icon' => 'map-pin'],
                                ['route' => 'admin.blog.index', 'label' => 'Blog Posts', 'icon' => 'pen'],
                                ['route' => 'admin.blog-categories.index', 'label' => 'Blog Categories', 'icon' => 'folder'],
                                ['route' => 'admin.tags.index', 'label' => 'Tags', 'icon' => 'tag'],
                                ['route' => 'admin.pages.index', 'label' => 'Pages', 'icon' => 'file'],
                            ],
                        ],
                        [
                            'label' => 'Inbox',
                            'items' => [
                                ['route' => 'admin.consultations.index', 'label' => 'Consultation Leads', 'icon' => 'inbox'],
                                ['route' => 'admin.contacts.index', 'label' => 'Contact Messages', 'icon' => 'mail'],
                            ],
                        ],
                        [
                            'label' => 'Site',
                            'items' => [
                                ['route' => 'admin.menus.index', 'label' => 'Menus', 'icon' => 'menu'],
                                ['route' => 'admin.media.index', 'label' => 'Media Library', 'icon' => 'image'],
                                ['route' => 'admin.settings.index', 'label' => 'Settings', 'icon' => 'cog'],
                                ['route' => 'admin.seo.index', 'label' => 'SEO', 'icon' => 'search'],
                                ['route' => 'admin.activity.index', 'label' => 'Activity Log', 'icon' => 'clock'],
                            ],
                        ],
                    ];
                @endphp

                @foreach($navigation as $group)
                    <div>
                        <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">{{ $group['label'] }}</p>
                        <ul class="space-y-0.5">
                            @foreach($group['items'] as $item)
                                @php $isActive = request()->routeIs($item['route']); @endphp
                                <li>
                                    <a
                                        href="{{ route($item['route']) }}"
                                        @class([
                                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors',
                                            'bg-accent-600 text-white' => $isActive,
                                            'text-stone-400 hover:bg-stone-900 hover:text-white' => ! $isActive,
                                        ])
                                        @if($isActive) aria-current="page" @endif
                                    >
                                        <span @class(['h-1.5 w-1.5 shrink-0 rounded-full', 'bg-white' => $isActive, 'bg-stone-600' => ! $isActive])></span>
                                        <span class="truncate">{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                @can('viewAny', App\Models\User::class)
                    <div>
                        <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">Access</p>
                        <ul class="space-y-0.5">
                            <li>
                                <a href="{{ route('admin.users.index') }}" @class([
                                    'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors',
                                    'bg-accent-600 text-white' => request()->routeIs('admin.users.*'),
                                    'text-stone-400 hover:bg-stone-900 hover:text-white' => ! request()->routeIs('admin.users.*'),
                                ])>
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-stone-600"></span>
                                    Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roles.index') }}" @class([
                                    'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors',
                                    'bg-accent-600 text-white' => request()->routeIs('admin.roles.*'),
                                    'text-stone-400 hover:bg-stone-900 hover:text-white' => ! request()->routeIs('admin.roles.*'),
                                ])>
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-stone-600"></span>
                                    Roles &amp; Permissions
                                </a>
                            </li>
                        </ul>
                    </div>
                @endcan
            </nav>

            <div class="border-t border-stone-800 p-4 text-sm">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-lg px-3 py-2 text-stone-400 hover:bg-stone-900 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14 4h6v6m0-6-8 8M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>
                    </svg>
                    View website
                </a>
            </div>
        </div>
    </aside>

    <div id="admin-overlay" class="fixed inset-0 z-40 hidden bg-stone-950/70 lg:hidden"></div>

    {{-- Main --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-30 border-b border-stone-200 bg-white/95 backdrop-blur dark:border-stone-800 dark:bg-stone-950/95">
            <div class="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
                <button
                    type="button"
                    class="btn-ghost p-2 lg:hidden"
                    aria-label="Open navigation"
                    @click="document.getElementById('admin-sidebar').classList.remove('-translate-x-full'); document.getElementById('admin-overlay').classList.remove('hidden')"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>

                <div class="min-w-0">
                    <h1 class="truncate font-display text-heading-md font-medium text-stone-900 dark:text-white">
                        @yield('heading', View::yieldContent('title', 'Dashboard'))
                    </h1>
                </div>

                <div class="ml-auto flex items-center gap-3">
                    @yield('actions')

                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-900"
                            aria-haspopup="true"
                            :aria-expanded="open ? 'true' : 'false'"
                            @click="open = ! open"
                            @click.outside="open = false"
                            @keydown.escape="open = false"
                        >
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent-600 text-caption font-medium text-white">
                                {{ Str::upper(Str::substr(Auth::user()?->name ?? 'A', 0, 2)) }}
                            </span>
                            <span class="hidden sm:block max-w-[10rem] truncate">{{ Auth::user()?->name }}</span>
                        </button>

                        <div
                            x-cloak
                            x-show="open"
                            x-transition.origin.top.right
                            class="absolute right-0 mt-2 w-56 rounded-lg border border-stone-200 bg-white p-2 shadow-elevated dark:border-stone-800 dark:bg-stone-900"
                        >
                            <p class="px-3 py-2 text-caption text-stone-500 dark:text-stone-400">{{ Auth::user()?->email }}</p>
                            <a href="{{ route('profile.edit') }}" class="block rounded-md px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-800">
                                My profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-sm text-stone-700 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-800">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main id="admin-content" class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @include('layouts.admin.partials.flash')

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
