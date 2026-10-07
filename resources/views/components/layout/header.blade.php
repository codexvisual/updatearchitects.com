@php
    use App\Models\Menu;

    $mainMenu = Menu::where('slug', 'main')->first();
    $menuItems = $mainMenu
        ? $mainMenu->items()->visible()->where('locale', app()->getLocale())->get()
        : collect();

    $currentPath = request()->path();
@endphp
<nav
    class="container relative"
    aria-label="Main navigation"
    x-data="{ mobileMenuOpen: false }"
    @keydown.escape="mobileMenuOpen = false"
>
    <div class="flex h-16 md:h-20 items-center justify-between gap-4">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group" aria-label="{{ config('app.name') }} - Home">
            <span class="inline-flex items-center justify-center rounded-lg bg-white p-1 shadow-soft">
                <img src="{{ asset('logo.jpg') }}" alt="{{ config('app.name') }} logo" class="h-8 md:h-9 w-auto" width="562" height="435" loading="eager">
            </span>
            <span class="flex flex-col leading-tight">
                <span class="font-display text-heading-sm font-medium tracking-tight text-stone-900 dark:text-white hidden sm:block">
                    Update Architects
                </span>
                <span class="font-display text-heading-sm font-medium tracking-tight text-stone-900 dark:text-white sm:hidden">
                    UA&amp;E
                </span>
                <span class="text-[11px] uppercase tracking-[0.18em] text-stone-400 hidden sm:block">Engineering</span>
            </span>
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden lg:flex lg:items-center lg:gap-0.5 flex-1 justify-center">
            <ul class="flex items-center gap-0.5" role="menubar">
                @foreach($menuItems as $item)
                    @php
                        $isActive = $currentPath === ltrim((string) $item->url, '/')
                            || ($item->url !== '/' && str_starts_with($currentPath, ltrim((string) $item->url, '/')));
                    @endphp
                    <li role="none">
                        <a
                            href="{{ $item->url ?: '#' }}"
                            @class([
                                'relative inline-flex items-center px-3 py-2 text-body-sm font-medium rounded-md transition-colors',
                                'text-stone-900 dark:text-white' => $isActive,
                                'text-stone-600 hover:text-stone-900 dark:text-stone-300 dark:hover:text-white' => ! $isActive,
                            ])
                            @if($isActive) aria-current="page" @endif
                            role="menuitem"
                        >
                            {{ $item->title }}
                            @if($isActive)
                                <span class="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-accent-600" aria-hidden="true"></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Right side --}}
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            {{-- Language Switcher --}}
            <div class="hidden sm:flex items-center rounded-lg border border-stone-200 p-0.5 dark:border-stone-700">
                @foreach(['en' => 'EN', 'bn' => 'BN'] as $code => $label)
                    <a
                        href="{{ route('locale.switch', ['locale' => $code]) }}"
                        hreflang="{{ $code }}"
                        @class([
                            'px-2.5 py-1 text-caption font-medium rounded-md transition-colors',
                            'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => app()->getLocale() === $code,
                            'text-stone-500 hover:text-stone-900 dark:text-stone-400 dark:hover:text-white' => app()->getLocale() !== $code,
                        ])
                        @if(app()->getLocale() === $code) aria-current="true" @endif
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Desktop CTAs --}}
            <a href="{{ route('contact') }}" class="btn-ghost hidden xl:inline-flex btn-sm">Contact Us</a>
            <a href="{{ route('consultation') }}" class="btn-primary hidden sm:inline-flex btn-sm">
                Start Your Project
            </a>

            {{-- Mobile Menu Button --}}
            <button
                type="button"
                class="btn-ghost p-2 lg:hidden"
                aria-label="Toggle menu"
                :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                aria-controls="mobile-menu"
                @click="mobileMenuOpen = ! mobileMenuOpen"
            >
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
                <svg x-cloak x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div
        id="mobile-menu"
        x-cloak
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden absolute inset-x-0 top-full z-50 border-b border-stone-200 bg-white shadow-elevated dark:border-stone-800 dark:bg-stone-950"
        @click.outside="mobileMenuOpen = false"
        @keydown.escape.window="mobileMenuOpen = false"
    >
        <div class="container py-6">
            <ul class="flex flex-col gap-1">
                @foreach($menuItems as $item)
                    @php $isActive = $currentPath === ltrim((string) $item->url, '/'); @endphp
                    <li>
                        <a
                            href="{{ $item->url ?: '#' }}"
                            @class([
                                'flex items-center justify-between rounded-lg px-4 py-3 text-body font-medium transition-colors',
                                'bg-stone-100 text-stone-900 dark:bg-stone-800 dark:text-white' => $isActive,
                                'text-stone-700 hover:bg-stone-50 dark:text-stone-200 dark:hover:bg-stone-900' => ! $isActive,
                            ])
                            @if($isActive) aria-current="page" @endif
                            @click="mobileMenuOpen = false"
                        >
                            {{ $item->title }}
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="m9 5 7 7-7 7"/>
                            </svg>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 pt-6 border-t border-stone-200 dark:border-stone-800 flex flex-col gap-3">
                <a href="{{ route('consultation') }}" class="btn-primary w-full" @click="mobileMenuOpen = false">Start Your Project</a>
                <a href="{{ route('contact') }}" class="btn-secondary w-full" @click="mobileMenuOpen = false">Contact Us</a>

                <div class="flex items-center rounded-lg border border-stone-200 p-0.5 dark:border-stone-700 sm:hidden">
                    @foreach(['en' => 'EN', 'bn' => 'BN'] as $code => $label)
                        <a
                            href="{{ route('locale.switch', ['locale' => $code]) }}"
                            hreflang="{{ $code }}"
                            @class([
                                'flex-1 text-center px-3 py-2 text-body-sm font-medium rounded-md transition-colors',
                                'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => app()->getLocale() === $code,
                                'text-stone-500 dark:text-stone-400' => app()->getLocale() !== $code,
                            ])
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>
