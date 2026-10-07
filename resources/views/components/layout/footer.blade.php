@php
    use App\Models\Menu;
    use App\Models\Office;
    use App\Models\ServiceCategory;
    use App\Models\Setting;

    $locale = app()->getLocale();

    $footerMenu = Menu::where('slug', 'footer')->first();
    $footerItems = $footerMenu
        ? $footerMenu->items()->visible()->where('locale', $locale)->get()
        : collect();

    $footerLinks = $footerItems->isNotEmpty()
        ? $footerItems
        : collect([
            ['title' => 'Home', 'url' => route('home')],
            ['title' => 'About', 'url' => route('about')],
            ['title' => 'Services', 'url' => route('services')],
            ['title' => 'Projects', 'url' => route('projects')],
            ['title' => 'Team', 'url' => route('team')],
            ['title' => 'Insights', 'url' => route('blog')],
            ['title' => 'Contact', 'url' => route('contact')],
        ])->map(fn (array $link) => (object) $link);

    // One link per discipline heading. The category page lists its own
    // services, so the footer does not need to repeat all 27 of them here.
    $serviceCategories = ServiceCategory::where('locale', $locale)
        ->whereHas('services', fn ($query) => $query->visible()->where('locale', $locale))
        ->orderBy('sort_order')
        ->get();

    $offices = Office::visible()->where('locale', $locale)->orderBy('sort_order')->get();

    // The branches currently share one number and one mailbox, so print them
    // once under the list rather than three times. The moment the branches
    // diverge, the per-office details are shown again automatically.
    $phones = $offices->pluck('phone')->filter()->unique()->values();
    $emails = $offices->pluck('email')->filter()->unique()->values();

    $sharedPhone = $phones->count() === 1 ? $phones->first() : null;
    $sharedEmail = $emails->count() === 1 ? $emails->first() : null;
    $showOfficeContact = $phones->count() > 1 || $emails->count() > 1;

    $socialLinks = Setting::getValue('footer.social_links', $locale, []);
    $socialLinks = is_array($socialLinks) ? $socialLinks : [];

    /** @var array<string, string|null> Filled brand glyphs; null renders the generic outbound arrow. */
    $socialGlyphs = [
        'facebook' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
        'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
        'linkedin' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
        'youtube' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
        'twitter' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
        'x' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
        'tiktok' => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
        'whatsapp' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z',
        'pinterest' => 'M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z',
        'default' => null,
    ];
@endphp
<footer class="bg-stone-950 text-stone-300" role="contentinfo">
    {{-- Invitation band ------------------------------------------------- --}}
    <div class="border-b border-stone-800/70 bg-stone-900/40">
        <div class="container flex flex-col gap-6 py-9 md:flex-row md:items-center md:justify-between md:py-11">
            <div class="max-w-2xl">
                <p class="text-overline text-accent-500 mb-2">Start here</p>
                <h2 class="font-display text-heading-lg text-white">Have a project in mind?</h2>
                <p class="text-body-sm text-stone-400 mt-2">
                    Share the site, the brief and the budget — we will come back with a clear next step.
                </p>
            </div>

            <div class="flex shrink-0 flex-wrap gap-3">
                <a href="{{ route('consultation') }}" class="btn bg-accent-600 text-white shadow-soft hover:bg-accent-700 focus-visible:ring-accent-500 focus-visible:ring-offset-stone-950">
                    Start your project
                </a>
                <a href="{{ route('contact') }}" class="btn border border-stone-700 text-stone-200 hover:border-stone-500 hover:text-white focus-visible:ring-accent-500 focus-visible:ring-offset-stone-950">
                    Talk to us
                </a>
            </div>
        </div>
    </div>

    <div class="container py-14 md:py-16">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-12">
            {{-- Brand --}}
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="group mb-5 flex items-center gap-2.5" aria-label="{{ config('app.name') }} - Home">
                    <span class="inline-flex items-center justify-center rounded-lg bg-white p-1 shadow-soft">
                        <img src="{{ asset('logo.jpg') }}" alt="{{ config('app.name') }} logo" class="h-9 w-auto" width="562" height="435" loading="lazy">
                    </span>
                    <span class="font-display text-heading-md font-medium tracking-tight text-white">Update Architects</span>
                </a>

                <p class="max-w-sm break-words text-body-sm text-stone-400">
                    {{ Setting::getValue('footer.tagline', $locale, 'Architecture • Engineering • Construction Consultancy • Interior Design') }}
                </p>

                <p class="mt-1.5 text-body-sm font-medium text-accent-400">
                    {{ Setting::getValue('tagline', $locale, 'Quality Design • Quality Construction') }}
                </p>

                <ul class="mt-6 flex flex-wrap gap-2.5">
                    <li>
                        <a
                            href="https://web.facebook.com/UpdateArchitects"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-stone-800 text-stone-400 transition-colors hover:border-accent-600 hover:bg-stone-900 hover:text-accent-400"
                            aria-label="Facebook (opens in a new tab)"
                            title="Facebook"
                        >
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a
                            href="mailto:updatearchites120@gmail.com"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-stone-800 text-stone-400 transition-colors hover:border-accent-600 hover:bg-stone-900 hover:text-accent-400"
                            aria-label="Email (opens in your mail app)"
                            title="Email"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a
                            href="https://wa.me/8801751585650"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-stone-800 text-stone-400 transition-colors hover:border-accent-600 hover:bg-stone-900 hover:text-accent-400"
                            aria-label="WhatsApp (opens in a new tab)"
                            title="WhatsApp"
                        >
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Company --}}
            <nav class="lg:col-span-2" aria-label="Quick links">
                <h2 class="text-overline text-stone-500 mb-4">Company</h2>
                <ul class="space-y-2.5">
                    @foreach($footerLinks as $item)
                        <li>
                            <a href="{{ $item->url }}" class="text-body-sm text-stone-400 transition-colors hover:text-accent-400">
                                {{ $item->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- Services --}}
            @if($serviceCategories->isNotEmpty())
                <nav class="lg:col-span-3" aria-label="Services">
                    <h2 class="text-overline text-stone-500 mb-4">Services</h2>
                    <ul class="space-y-2.5">
                        @foreach($serviceCategories as $category)
                            <li>
                                @if(Route::has("services.{$category->slug}"))
                                    <a href="{{ route("services.{$category->slug}") }}" class="text-body-sm text-stone-400 transition-colors hover:text-accent-400">
                                        {{ $category->name }}
                                    </a>
                                @else
                                    <span class="text-body-sm text-stone-400">{{ $category->name }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('services') }}" class="mt-4 inline-flex items-center gap-1.5 text-body-sm font-medium text-accent-400 transition-colors hover:text-accent-300">
                        View all services
                        <span aria-hidden="true">→</span>
                    </a>
                </nav>
            @endif

            {{-- Offices --}}
            <address class="not-italic lg:col-span-3" aria-label="Office locations">
                <h2 class="text-overline text-stone-500 mb-4">Offices</h2>

                <ul class="space-y-4">
                    @forelse($offices as $office)
                        <li>
                            <h3 class="text-body-sm font-medium text-white">{{ $office->name }}</h3>

                            @if($office->address)
                                <p class="text-body-sm text-stone-400">{!! nl2br(e($office->address)) !!}</p>
                            @endif

                            @if($showOfficeContact)
                                @if($office->phone)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}" class="mt-1 block text-body-sm text-stone-400 transition-colors hover:text-accent-400">
                                        {{ $office->phone }}
                                    </a>
                                @endif
                                @if($office->email)
                                    <a href="mailto:{{ $office->email }}" class="mt-1 block break-all text-body-sm text-stone-400 transition-colors hover:text-accent-400">
                                        {{ $office->email }}
                                    </a>
                                @endif
                            @endif
                        </li>
                    @empty
                        <li class="text-body-sm text-stone-500">Office details will be published here shortly.</li>
                    @endforelse
                </ul>

                @if($sharedPhone || $sharedEmail)
                    <div class="mt-5 space-y-1.5 border-t border-stone-800 pt-4">
                        @if($sharedPhone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $sharedPhone) }}" class="block text-body-sm text-stone-400 transition-colors hover:text-accent-400">
                                {{ $sharedPhone }}
                            </a>
                        @endif
                        @if($sharedEmail)
                            <a href="mailto:{{ $sharedEmail }}" class="block break-all text-body-sm text-stone-400 transition-colors hover:text-accent-400">
                                {{ $sharedEmail }}
                            </a>
                        @endif
                    </div>
                @endif
            </address>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-12 flex flex-col gap-4 border-t border-stone-800 pt-6 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1.5">
                <p class="text-body-sm text-stone-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>

                {{--
                    Developer credit. Deliberately written as a literal in this
                    template rather than read through Setting::getValue(), so it
                    cannot be altered or removed from the admin panel.
                --}}
                <p class="text-caption text-stone-400">
                    Developed by
                    <a
                        href="https://filofa.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="font-medium text-stone-300 underline underline-offset-4 transition-colors hover:text-accent-400"
                    >Filofa Digital Solutions</a>
                </p>
            </div>

            <nav aria-label="Legal" class="flex flex-wrap gap-x-6 gap-y-2">
                <a href="{{ route('privacy') }}" class="text-body-sm text-stone-400 transition-colors hover:text-accent-400">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-body-sm text-stone-400 transition-colors hover:text-accent-400">Terms &amp; Conditions</a>
                <a href="{{ route('international-sop') }}" class="text-body-sm text-stone-400 transition-colors hover:text-accent-400">International SOP</a>
                <a href="{{ route('sitemap') }}" class="text-body-sm text-stone-400 transition-colors hover:text-accent-400">Sitemap</a>
            </nav>
        </div>
    </div>
</footer>
