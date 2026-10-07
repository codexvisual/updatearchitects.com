@php
    use App\Models\Setting;

    $locale = app()->getLocale();

    $disciplines = $services
        ->groupBy(fn($service) => $service->category?->name ?? 'Services')
        ->map(fn($group, $categoryName) => [
            'title' => $categoryName,
            'items' => $group->pluck('name')->filter()->values(),
        ])
        ->values();
@endphp
<x-layout.app :title="$seo['title']" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema">
    {{-- Hero --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-24">
            <p class="text-overline text-accent-600 mb-4">About Us</p>
            <h1 class="font-display text-display-lg max-w-3xl mb-6">
                {{ Setting::getValue('about.heading', $locale, 'Architecture, Engineering & Construction Consultancy') }}
            </h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-3xl">
                {{ Setting::getValue('about.intro', $locale, 'Update Architects & Engineering delivers architectural design, structural and geotechnical engineering, construction consultancy, electrical design and interior design from a single team — so design intent and site reality stay aligned.') }}
            </p>
        </div>
    </section>

    {{-- Mission / Approach --}}
    <section class="section" aria-labelledby="approach-heading">
        <div class="container">
            <div class="grid gap-12 lg:grid-cols-2">
                <div>
                    <p class="text-overline text-accent-600 mb-4">Our Approach</p>
                    <h2 id="approach-heading" class="font-display text-display-md mb-6">
                        {{ Setting::getValue('about.approach_heading', $locale, 'Quality design. Quality construction.') }}
                    </h2>
                    <div class="prose">
                        {{ Setting::getValue('about.approach_content', $locale, 'Every commission starts with the site and the client brief. We survey, analyse and document before a single line is drawn, then carry the same level of attention through drawings, structural calculations, approvals and site supervision.') }}
                    </div>

                    <div class="mt-10 rounded-xl border border-stone-200 bg-white p-6 shadow-soft dark:border-stone-800 dark:bg-stone-900/50">
                        <x-illustrations.elevation class="w-full text-stone-300 dark:text-stone-700" />
                        <p class="mt-4 text-caption text-stone-500 dark:text-stone-400 text-center">Concept elevation — an early idea we develop with a client.</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @php
                        $principles = [
                            ['title' => 'Site First', 'text' => 'Every project begins with proper survey, documentation and analysis of the ground conditions.', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&q=80'],
                            ['title' => 'One Team', 'text' => 'Architecture, structural, geotechnical, electrical and interior under a single roof.', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75', 'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&q=80'],
                            ['title' => 'Documented Process', 'text' => 'Drawings, calculations and progress records so decisions stay traceable.', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8', 'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&q=80'],
                            ['title' => 'On-Site Presence', 'text' => 'Regular supervision through foundation, structure and finishing stages.', 'icon' => 'M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0zM12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z', 'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=400&q=80'],
                        ];
                    @endphp
                    @foreach($principles as $index => $principle)
                        <div class="group relative rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 overflow-hidden hover:border-accent-300 dark:hover:border-accent-700 hover:shadow-card hover:-translate-y-1 transition-all duration-300">
                            <div class="h-24 overflow-hidden">
                                <img src="{{ $principle['image'] }}" alt="{{ $principle['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" width="400" height="200">
                            </div>
                            <div class="p-5">
                                <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300 mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="{{ $principle['icon'] }}"/>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-stone-900 dark:text-white mb-2">{{ $principle['title'] }}</h3>
                                <p class="text-body-sm text-stone-600 dark:text-stone-400">{{ $principle['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Disciplines --}}
    @if($disciplines->isNotEmpty())
        <section class="section bg-stone-50 dark:bg-stone-900/50" aria-labelledby="disciplines-heading">
            <div class="container">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <p class="text-overline text-accent-600 mb-4">What We Do</p>
                    <h2 id="disciplines-heading" class="font-display text-display-md">In-house disciplines</h2>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                        $disciplineImages = [
                            'https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=400&q=80',
                            'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&q=80',
                            'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&q=80',
                            'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=400&q=80',
                            'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=400&q=80',
                            'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=400&q=80',
                            'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&q=80',
                            'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=400&q=80',
                        ];
                    @endphp
                    @foreach($disciplines as $index => $discipline)
                        <div class="group relative rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900 overflow-hidden hover:border-accent-300 dark:hover:border-accent-700 hover:shadow-card hover:-translate-y-1 transition-all duration-300">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-500 to-accent-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                            <div class="h-32 overflow-hidden">
                                <img src="{{ $disciplineImages[$index % count($disciplineImages)] }}" alt="{{ $discipline['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" width="400" height="200">
                            </div>
                            <div class="p-5">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300 mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5"/>
                                    </svg>
                                </div>
                                <h3 class="font-display text-heading-md mb-4">{{ $discipline['title'] }}</h3>
                                <ul class="space-y-2">
                                    @foreach($discipline['items'] as $item)
                                        <li class="flex items-start gap-2 text-body-sm text-stone-600 dark:text-stone-400">
                                            <svg class="h-4 w-4 mt-1 shrink-0 text-accent-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                                <path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('services') }}" class="btn-secondary">See all services</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Process --}}
    <section class="section" aria-labelledby="about-process-heading">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <p class="text-overline text-accent-600 mb-4">How We Work</p>
                <h2 id="about-process-heading" class="font-display text-display-md">From brief to handover</h2>
            </div>

            <ol class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $stages = [
                        ['title' => 'Brief & Site Visit', 'text' => 'Understand the requirement, budget band and site conditions.', 'icon' => 'M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0zM12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z', 'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&q=80'],
                        ['title' => 'Survey & Concept', 'text' => 'Topographic survey, soil investigation and concept options.', 'icon' => 'M9 20l-5.447-2.724A1 1 0 0 1 3 16.382V5.618a1 1 0 0 1 1.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0 0 21 18.382V7.618a1 1 0 0 0-.553-.894L15 4m0 13V4m0 0L9 7', 'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&q=80'],
                        ['title' => 'Design & Approvals', 'text' => 'Architectural, structural and electrical design plus RAJUK paperwork.', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8', 'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&q=80'],
                        ['title' => 'Construction', 'text' => 'Site supervision, quality control and progress reporting.', 'icon' => 'M2 20h20M4 20V8l8-5 8 5v12M9 20v-6h6v6', 'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&q=80'],
                    ];
                @endphp
                @foreach($stages as $index => $stage)
                    <li class="group relative rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 overflow-hidden hover:border-accent-300 dark:hover:border-accent-700 hover:shadow-card hover:-translate-y-1 transition-all duration-300">
                        <div class="h-28 overflow-hidden">
                            <img src="{{ $stage['image'] }}" alt="{{ $stage['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" width="400" height="200">
                        </div>
                        <div class="p-5 relative">
                            <div class="absolute -top-5 right-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-accent-600 text-white text-body-sm font-medium shadow-elevated group-hover:scale-110 transition-transform duration-300">
                                {{ $index + 1 }}
                            </div>
                            <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300 mb-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $stage['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-stone-900 dark:text-white mb-2">{{ $stage['title'] }}</h3>
                            <p class="text-body-sm text-stone-600 dark:text-stone-400">{{ $stage['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Offices --}}
    @if($offices->isNotEmpty())
        <section class="section" aria-labelledby="about-offices-heading">
            <div class="container">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <p class="text-overline text-accent-600 mb-4">Where We Work</p>
                    <h2 id="about-offices-heading" class="font-display text-display-md">Our offices</h2>
                </div>

                @php
                    $officeImages = [
                        'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&q=80',
                        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&q=80',
                        'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&q=80',
                    ];
                @endphp
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($offices as $index => $office)
                        <div class="group relative rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900 overflow-hidden hover:border-accent-300 dark:hover:border-accent-700 hover:shadow-card hover:-translate-y-1 transition-all duration-300 h-full">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-500 to-accent-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                            <div class="h-32 overflow-hidden">
                                <img src="{{ $officeImages[$index % count($officeImages)] }}" alt="{{ $office->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" width="400" height="200">
                            </div>
                            <div class="p-6">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300 mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0zM12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    </svg>
                                </div>
                                <h3 class="font-display text-heading-md mb-3">{{ $office->name }}</h3>
                                <address class="not-italic space-y-2 text-body-sm text-stone-600 dark:text-stone-400 not-last:mb-0">
                                    @if($office->address)
                                        <p>{!! nl2br(e($office->address)) !!}</p>
                                    @endif
                                    @if($office->phone)
                                        <p><a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}" class="hover:text-accent-600">{{ $office->phone }}</a></p>
                                    @endif
                                    @if($office->email)
                                        <p><a href="mailto:{{ $office->email }}" class="hover:text-accent-600 break-all">{{ $office->email }}</a></p>
                                    @endif
                                </address>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="section bg-accent-700 text-white">
        <div class="container text-center">
            <h2 class="font-display text-display-lg mb-4">Let's talk about your project</h2>
            <p class="text-body-lg text-accent-100 max-w-2xl mx-auto mb-8">
                Share your requirements, timeline and budget range so we can propose the right scope.
            </p>
            <a href="{{ route('consultation') }}" class="btn-secondary bg-white text-accent-700 hover:bg-accent-50 border-transparent shadow-elevated">
                Start Your Project
            </a>
        </div>
    </section>
</x-layout.app>