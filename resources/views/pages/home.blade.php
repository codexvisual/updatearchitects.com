@php
    use App\Models\Setting;

    $locale = app()->getLocale();
@endphp
<x-layout.app :title="$seo['title'] ?? config('app.name')" :description="$seo['description'] ?? ''" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema ?? []">
    {{-- Hero Carousel --}}
    @php
        $useCmsSlides = $heroSlidesCms->isNotEmpty();
        $heroSlides = $useCmsSlides ? $heroSlidesCms->count() : $heroServices->count();
    @endphp

    <section
        class="relative bg-stone-900 overflow-hidden"
        aria-roledescription="carousel"
        aria-label="Highlights"
        x-data="heroCarousel({{ $heroSlides }})"
        x-init="init()"
        @keydown.left.prevent="prev()"
        @keydown.right.prevent="next()"
        @mouseenter="stop()"
        @mouseleave="start()"
        @focusin="stop()"
        @focusout="start()"
        @touchstart.passive="onTouchStart($event)"
        @touchend="onTouchEnd($event)"
    >
        <div class="grid">
            @if($useCmsSlides)
                {{-- CMS-managed slides --}}
                @foreach($heroSlidesCms as $i => $slide)
                    <div
                        x-show="index === {{ $i }}"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-[1.02]"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-[0.99]"
                        class="col-start-1 row-start-1 relative flex min-h-[85vh] items-center justify-center overflow-hidden"
                        role="group"
                        aria-roledescription="slide"
                        aria-label="{{ $i + 1 }} of {{ $heroSlides }}"
                    >
                        <div class="absolute inset-0 z-0">
                            @if($slide->featuredImage)
                                <picture>
                                    @if($slide->mobileImage)
                                        <source media="(max-width: 767px)" srcset="{{ $slide->mobileImage->responsiveSrcset() }}" sizes="100vw">
                                    @endif
                                    <img src="{{ $slide->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $slide->featuredImage->responsiveSrcset() }}" sizes="100vw" alt="{{ $slide->title }}" class="slide-kenburns w-full h-full object-cover opacity-60" width="1600" height="900" @if($i === 0) fetchpriority="high" @else loading="lazy" @endif>
                                </picture>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-stone-900 via-stone-800 to-stone-950"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-r from-stone-950/80 via-stone-950/40 to-stone-950/10"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/60 via-transparent to-transparent"></div>
                        </div>

                        <div class="container relative z-10 py-20 text-center md:text-left md:max-w-4xl md:mx-0">
                            @if($slide->eyebrow)
                                <p class="text-body-sm font-semibold tracking-[0.2em] uppercase text-accent-400 mb-4">{{ $slide->eyebrow }}</p>
                            @endif

                            @if($i === 0)
                                <h1 class="font-display text-display-md md:text-display-lg text-white max-w-3xl mb-6 text-balance leading-tight">{{ $slide->title }}</h1>
                            @else
                                <h2 class="font-display text-display-md md:text-display-lg text-white max-w-3xl mb-6 text-balance leading-tight">{{ $slide->title }}</h2>
                            @endif

                            @if($slide->subtitle)
                                <p class="text-body-lg text-stone-200 max-w-xl mb-10 leading-relaxed">{{ $slide->subtitle }}</p>
                            @endif

                            @if($slide->cta_label || $slide->secondary_cta_label)
                                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                    @if($slide->cta_label && $slide->cta_url)
                                        <a href="{{ $slide->cta_url }}" class="btn-primary btn-lg bg-accent-600 hover:bg-accent-700 border-accent-600">{{ $slide->cta_label }}</a>
                                    @endif
                                    @if($slide->secondary_cta_label && $slide->secondary_cta_url)
                                        <a href="{{ $slide->secondary_cta_url }}" class="btn-outline btn-lg text-white border-white hover:bg-white hover:text-stone-900">{{ $slide->secondary_cta_label }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
            {{-- Service slides --}}
            @foreach($heroServices as $i => $service)
                @php $slide = $i + 1; @endphp
                <div
                    x-show="index === {{ $slide - 1 }}"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 scale-[1.02]"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-[0.99]"
                    class="col-start-1 row-start-1 relative flex min-h-[85vh] items-center justify-center overflow-hidden"
                    role="group"
                    aria-roledescription="slide"
                    aria-label="{{ $slide }} of {{ $heroSlides }}"
                >
                    <div class="absolute inset-0 z-0">
                        @if($service->featuredImage)
                            <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $service->featuredImage->responsiveSrcset() }}" sizes="100vw" alt="{{ $service->name }}" class="slide-kenburns w-full h-full object-cover opacity-60" loading="lazy" width="1440" height="1080">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-stone-900 via-stone-800 to-accent-950"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-r from-stone-950/80 via-stone-950/40 to-stone-950/10"></div>
                    </div>

                    <div class="container relative z-10 py-20 text-center md:text-left">
                        <p class="text-overline text-accent-400 mb-6">Our Services</p>
                        <h2 class="font-display text-display-md text-white max-w-4xl mb-6 text-balance">{{ $service->name }}</h2>
                        @if($service->short_description)
                            <p class="text-body text-stone-300 max-w-2xl mb-10">{{ $service->short_description }}</p>
                        @endif
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn-primary btn-lg bg-accent-600 hover:bg-accent-700 border-accent-600">
                                Explore {{ $service->name }}
                            </a>
                            <a href="{{ route('consultation') }}" class="btn-outline btn-lg text-white border-white hover:bg-white hover:text-stone-900">
                                Start Your Project
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>

        @if($heroSlides > 1)
            {{-- Previous / Next --}}
            <button type="button" @click="prev()" aria-label="Previous slide"
                class="absolute left-4 md:left-8 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-sm transition-colors hover:bg-accent-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" @click="next()" aria-label="Next slide"
                class="absolute right-4 md:right-8 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-sm transition-colors hover:bg-accent-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Pagination dots --}}
            <div class="absolute inset-x-0 bottom-8 z-20 flex items-center justify-center gap-2.5">
                @for($i = 0; $i < $heroSlides; $i++)
                    <button
                        type="button"
                        @click="go({{ $i }})"
                        :aria-current="index === {{ $i }} ? 'true' : 'false'"
                        aria-label="Go to slide {{ $i + 1 }}"
                        class="h-2.5 rounded-full transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                        :class="index === {{ $i }} ? 'w-8 bg-accent-500' : 'w-2.5 bg-white/40 hover:bg-white/70'"
                    ></button>
                @endfor
            </div>

            {{-- Slide counter --}}
            <span class="absolute bottom-8 right-5 z-20 text-body-sm text-white/70 tabular-nums md:right-8" x-text="(index + 1) + ' / {{ $heroSlides }}'"></span>
        @endif
    </section>

    {{-- Company Introduction --}}
    <section class="section section-alt" aria-labelledby="intro-heading">
        <div class="container">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-overline text-accent-600 mb-4">Who We Are</p>
                    <h2 id="intro-heading" class="font-display text-display-md mb-6">
                        {{ Setting::getValue('intro.heading', $locale, 'Building Tomorrow, Today') }}
                    </h2>
                    <div class="prose prose-lg">
                        {{ Setting::getValue('intro.content', $locale, 'Update Architects & Engineering is a multidisciplinary consultancy providing architectural design, structural and geotechnical engineering, construction consultancy, electrical design and interior design services.') }}
                    </div>
                    <a href="{{ route('about') }}" class="btn-secondary mt-4">Learn About the Practice</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @php
                        $introFeatures = [
                            ['title' => 'Architecture', 'text' => Setting::getValue('intro.architecture', $locale, 'Architectural planning and design')],
                            ['title' => 'Engineering', 'text' => Setting::getValue('intro.engineering', $locale, 'Structural & geotechnical engineering')],
                            ['title' => 'Construction', 'text' => Setting::getValue('intro.construction', $locale, 'Site supervision & consultancy')],
                            ['title' => 'Interior', 'text' => Setting::getValue('intro.interior', $locale, 'Interior design & space planning')],
                        ];
                    @endphp
                    @foreach($introFeatures as $feature)
                        <div class="text-center p-6 bg-stone-50 rounded-xl border border-stone-200 dark:bg-stone-900 dark:border-stone-800 feature-tile min-w-0">
                            <h3 class="font-medium text-stone-900 dark:text-white mb-2 break-words">{{ $feature['title'] }}</h3>
                            <p class="text-body-sm text-stone-600 dark:text-stone-400 break-words">{{ $feature['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Project --}}
    @if($featuredProject)
        <section class="section bg-stone-50 dark:bg-stone-900/50" aria-labelledby="featured-heading">
            <div class="container">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1">
                        @if($featuredProject->featuredImage)
                            <div class="group/photo [perspective:1400px]">
                                <img src="{{ $featuredProject->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $featuredProject->featuredImage->responsiveSrcset() }}" sizes="(min-width: 768px) 50vw, 100vw" alt="{{ $featuredProject->title }}" class="rounded-xl shadow-elevated w-full transition-transform duration-700 [transition-timing-function:cubic-bezier(0.19,1,0.22,1)] saturate-90 hover:scale-[1.04] hover:-rotate-1.5 hover:saturate-110 hover:shadow-2xl" width="800" height="600" loading="lazy">
                            </div>
                        @else
                            <x-ui.placeholder ratio="video" icon="photo" label="Project photo to be added" class="rounded-xl shadow-soft" />
                        @endif
                    </div>
                    <div class="order-1 md:order-2">
                        <p class="text-overline text-accent-600 mb-4">Featured Project</p>
                        <h2 id="featured-heading" class="font-display text-display-lg mb-6">{{ $featuredProject->title }}</h2>

                        <div class="space-y-4 mb-8">
                            @if($featuredProject->location)
                                <p class="text-body-sm text-stone-600 dark:text-stone-400">
                                    <strong class="text-stone-900 dark:text-white">Location:</strong>
                                    {{ $featuredProject->location }}
                                </p>
                            @endif
                            @if($featuredProject->area)
                                <p class="text-body-sm text-stone-600 dark:text-stone-400">
                                    <strong class="text-stone-900 dark:text-white">Area:</strong>
                                    {{ $featuredProject->area }}
                                </p>
                            @endif
                            @if($featuredProject->status)
                                <p class="text-body-sm text-stone-600 dark:text-stone-400">
                                    <strong class="text-stone-900 dark:text-white">Status:</strong>
                                    <x-ui.badge variant="{{ $featuredProject->status === 'completed' ? 'primary' : 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $featuredProject->status)) }}
                                    </x-ui.badge>
                                </p>
                            @endif
                        </div>

                        @if($featuredProject->summary || $featuredProject->description)
                            <div class="prose prose-lg mb-8">
                                {{ $featuredProject->summary ?? Str::limit($featuredProject->description, 300) }}
                            </div>
                        @endif

                        <a href="{{ route('projects.show', $featuredProject->slug) }}" class="btn-primary">
                            View Project Details
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Core Services --}}
    <section class="section bg-accent-50/50 dark:bg-stone-950/20" aria-labelledby="services-heading">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <p class="text-overline text-accent-600 mb-4">Our Services</p>
                <h2 id="services-heading" class="font-display text-display-md mb-6">Comprehensive Design & Engineering Solutions</h2>
                <p class="text-body-lg text-stone-600 dark:text-stone-400">From concept to completion, we deliver integrated services that ensure quality, safety, and aesthetic excellence.</p>
            </div>

            <div class="grid-auto-fit-3 gap-8">
                @forelse($services as $service)
                    <x-ui.card variant="premium" href="{{ route('services.show', $service->slug) }}" class="group p-8 flex flex-col h-full animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                        @if($service->featuredImage)
                            <div class="card-media mb-6 rounded-lg">
                                <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $service->featuredImage->responsiveSrcset() }}" sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw" alt="{{ $service->name }}" class="w-full aspect-[4/3] object-cover" width="600" height="450" loading="lazy">
                            </div>
                        @else
                            <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300">
                                <x-icons.icon :name="$service->slug" class="h-6 w-6" />
                            </div>
                        @endif

                        <h3 class="font-display text-heading-lg mb-3">{{ $service->name }}</h3>

                        <p class="text-body-sm text-stone-600 dark:text-stone-400 mb-6 flex-1">{{ $service->short_description }}</p>

                        <span class="inline-flex items-center gap-2 text-body-sm font-medium text-accent-600 transition-transform duration-300 group-hover:translate-x-1">
                            Learn More <span aria-hidden="true">→</span>
                        </span>
                    </x-ui.card>
                @empty
                    <p class="col-span-full text-center text-stone-500 dark:text-stone-400">Services will be published here shortly.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Project Portfolio --}}
    <section class="section relative bg-gradient-to-br from-stone-900 to-stone-950 text-white border-t border-stone-800" aria-labelledby="portfolio-heading">
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-40 text-white opacity-[0.06]">
            <x-illustrations.skyline class="h-full w-full" />
        </div>
        <div class="pointer-events-none absolute inset-0 blueprint-grid" aria-hidden="true"></div>
        <div class="container relative">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16">
                <div>
                    <p class="text-overline text-accent-400 mb-4">Portfolio</p>
                    <h2 id="portfolio-heading" class="font-display text-display-md">Featured Projects</h2>
                </div>
                <a href="{{ route('projects') }}" class="btn-ghost text-white hover:bg-white/10 mt-4 md:mt-0">View All Projects →</a>
            </div>

            <div class="grid-auto-fit gap-6">
                @forelse($projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="group block">
                        <div class="rounded-lg overflow-hidden mb-4 ring-1 ring-stone-800 transition-all duration-300 group-hover:ring-accent-500/60">
                            @if($project->featuredImage)
                                <img src="{{ $project->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $project->featuredImage->responsiveSrcset() }}" sizes="(min-width: 1280px) 25vw, (min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" alt="{{ $project->title }}" class="w-full aspect-video object-cover transition-[transform,filter] duration-500 saturate-90 group-hover:scale-105 group-hover:saturate-110" loading="lazy" width="400" height="300">
                            @else
                                <x-ui.placeholder ratio="video" icon="photo" class="bg-stone-800" />
                            @endif
                        </div>
                        <h3 class="font-medium text-white mb-1 group-hover:text-accent-400 transition-colors">{{ $project->title }}</h3>
                        <p class="text-body-sm text-stone-400">
                            {{ collect([$project->location, Str::headline((string) $project->category)])->filter()->join(' • ') }}
                        </p>
                    </a>
                @empty
                    <p class="col-span-full text-center text-stone-400">No published projects yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Interior Design Program --}}
    @php
        $interiorService = $services->firstWhere('slug', 'interior-design') ?? $services->where('category.name', 'Interior Design')->first();
        $interiorFeatures = [
            Setting::getValue('interior.kitchen_cabinet', $locale, 'Kitchen Cabinet'),
            Setting::getValue('interior.modular_kitchen', $locale, 'Modular Kitchen'),
            Setting::getValue('interior.residential', $locale, 'Residential Interior'),
            Setting::getValue('interior.luxury', $locale, 'Luxury Interior'),
            Setting::getValue('interior.office', $locale, 'Office Interior'),
            Setting::getValue('interior.hotel', $locale, 'Hotel Interior'),
        ];
    @endphp

    @if($interiorService)
        <section class="section section-alt" aria-labelledby="interior-heading">
            <div class="container">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <p class="text-overline text-accent-600 mb-4">Update Interior Program</p>
                        <h2 id="interior-heading" class="font-display text-display-md mb-6">
                            {{ Setting::getValue('interior.heading', $locale, 'Elegant Interior Solutions') }}
                        </h2>
                        <p class="text-body-lg text-stone-600 dark:text-stone-400 mb-8">
                            {{ $interiorService->short_description }}
                        </p>
                        <a href="{{ route('services.show', $interiorService->slug) }}" class="btn-primary mb-10">Explore Interior Design</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($interiorFeatures as $feature)
                            <div class="flex items-center gap-3 border border-stone-200 rounded-lg p-4 dark:border-stone-800 bg-stone-50 dark:bg-stone-900/40 transition-colors hover:border-accent-300 dark:hover:border-accent-800">
                                <svg class="h-5 w-5 shrink-0 text-accent-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p class="font-medium text-stone-900 dark:text-white">{{ $feature }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Team Preview --}}
    <section class="section bg-stone-50 dark:bg-stone-900/50" aria-labelledby="team-heading">
        <div class="container">
            <div class="text-center mb-16">
                <p class="text-overline text-accent-600 mb-4">Our Team</p>
                <h2 id="team-heading" class="font-display text-display-md mb-4">Meet the Experts</h2>
                <p class="text-body-lg text-stone-600 dark:text-stone-400">Dedicated professionals committed to excellence</p>
            </div>

            <div class="grid-auto-fit-2 gap-8">
                @forelse($team as $member)
                    <x-ui.card variant="premium" href="{{ route('team.show', $member->slug) }}" class="group flex items-center gap-5 p-6 animate-fade-in-up" style="animation-delay: {{ ($loop->index % 2) * 100 }}ms">
                        <x-ui.avatar :name="$member->name" :photo="$member->photo" size="md" />

                        <div class="min-w-0">
                            <h3 class="font-display text-heading-sm mb-1 group-hover:text-accent-600 transition-colors">{{ $member->name }}</h3>

                            @if($member->designation)
                                <p class="text-body-sm font-medium text-accent-600">{{ $member->designation }}</p>
                            @endif

                            @if($member->qualification)
                                <p class="text-body-sm text-stone-600 dark:text-stone-400 mt-1">{{ $member->qualification }}</p>
                            @endif
                        </div>
                    </x-ui.card>
                @empty
                    <p class="col-span-full text-center text-stone-500 dark:text-stone-400">Team profiles will be published here shortly.</p>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('team') }}" class="btn-secondary">View Full Team</a>
            </div>
        </div>
    </section>

    {{-- Latest Insights --}}
    <section class="section bg-stone-50 dark:bg-stone-900/50" aria-labelledby="insights-heading">
        <div class="container">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16">
                <div>
                    <p class="text-overline text-accent-600 mb-4">Insights</p>
                    <h2 id="insights-heading" class="font-display text-display-md">Latest Articles &amp; Guides</h2>
                </div>
                <a href="{{ route('blog') }}" class="btn-ghost mt-4 md:mt-0">View All Insights →</a>
            </div>

            <div class="grid-auto-fit-3 gap-8">
                @forelse($latestPosts as $post)
                    <x-ui.card variant="premium" href="{{ route('blog.show', $post->slug) }}" class="group p-8 flex flex-col h-full animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                        <div class="mb-4 flex items-center gap-3">
                            @if($post->category)
                                <span class="inline-flex items-center rounded-full bg-accent-50 px-3 py-1 text-caption font-medium text-accent-700 dark:bg-accent-900/30 dark:text-accent-300">
                                    {{ $post->category->name }}
                                </span>
                            @endif
                            <span class="text-caption text-stone-500 dark:text-stone-400">
                                {{ $post->published_at?->format('d M Y') }}
                            </span>
                        </div>

                        <h3 class="font-display text-heading-lg mb-3 group-hover:text-accent-600 transition-colors">{{ $post->title }}</h3>

                        <p class="text-body-sm text-stone-600 dark:text-stone-400 mb-6 flex-1">{{ $post->excerpt }}</p>

                        <span class="inline-flex items-center gap-2 text-body-sm font-medium text-accent-600 transition-transform duration-300 group-hover:translate-x-1">
                            Read Article <span aria-hidden="true">→</span>
                        </span>
                    </x-ui.card>
                @empty
                    <p class="col-span-full text-center text-stone-500 dark:text-stone-400">Insights will be published here shortly.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="section section-alt" aria-labelledby="why-heading">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 id="why-heading" class="font-display text-display-md mb-6">Why Choose Update Architects</h2>
                <p class="text-body-lg text-stone-600 dark:text-stone-400">Our commitment to quality and client satisfaction sets us apart.</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $differentiators = [
                        ['title' => 'Integrated Approach', 'text' => Setting::getValue('why.integrated_approach', $locale, 'Seamless coordination between architecture, engineering, and construction teams.'), 'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=600&q=80'],
                        ['title' => 'Quality Assurance', 'text' => Setting::getValue('why.quality', $locale, 'Quality control at every stage of design and construction.'), 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&q=80'],
                        ['title' => 'Local Expertise', 'text' => Setting::getValue('why.local', $locale, 'Practical understanding of Bangladesh building practice and approvals.'), 'image' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=600&q=80'],
                        ['title' => 'Innovation', 'text' => Setting::getValue('why.innovation', $locale, 'Modern design principles paired with proven engineering solutions.'), 'image' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=600&q=80'],
                        ['title' => 'Client Partnership', 'text' => Setting::getValue('why.partnership', $locale, 'We treat your project as if it were our own.'), 'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=600&q=80'],
                        ['title' => 'Transparency', 'text' => Setting::getValue('why.transparency', $locale, 'Clear communication and honest progress reporting.'), 'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&q=80'],
                    ];
                @endphp
                @foreach($differentiators as $item)
                    <div class="group relative rounded-xl overflow-hidden border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 hover:shadow-card hover:-translate-y-1 transition-all duration-300">
                        <div class="h-40 overflow-hidden">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" width="600" height="400">
                        </div>
                        <div class="p-6">
                            <h3 class="font-medium text-stone-900 dark:text-white mb-3 break-words">{{ $item['title'] }}</h3>
                            <p class="text-body-sm text-stone-600 dark:text-stone-400 break-words">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="section bg-stone-50 dark:bg-stone-900/50" aria-labelledby="process-heading">
        <div class="container">
            <div class="text-center mb-16">
                <h2 id="process-heading" class="font-display text-display-md mb-4">Our Design & Construction Process</h2>
                <p class="text-body-lg text-stone-600 dark:text-stone-400">A systematic approach to delivering quality results</p>
            </div>

            <ol class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $process = [
                        ['step' => '01', 'title' => 'Site Survey', 'text' => 'Comprehensive site analysis and assessment.', 'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&q=80'],
                        ['step' => '02', 'title' => 'Concept Design', 'text' => 'Preliminary design ideas and client consultation.', 'image' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=400&q=80'],
                        ['step' => '03', 'title' => 'Architectural Design', 'text' => 'Detailed architectural drawings and documentation.', 'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&q=80'],
                        ['step' => '04', 'title' => 'Structural Design', 'text' => 'Engineering calculations and structural plans.', 'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&q=80'],
                        ['step' => '05', 'title' => 'Foundation', 'text' => 'Site preparation and foundation work.', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&q=80'],
                        ['step' => '06', 'title' => 'Base Casting', 'text' => 'Ground floor structural slab casting.', 'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=400&q=80'],
                        ['step' => '07', 'title' => 'Construction', 'text' => 'Columns, slabs, and finishing work.', 'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&q=80'],
                        ['step' => '08', 'title' => 'Handover', 'text' => 'Final inspection and project delivery.', 'image' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&q=80'],
                    ];
                @endphp
                @foreach($process as $step)
                    <li class="group relative rounded-xl overflow-hidden border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 hover:shadow-card hover:-translate-y-1 transition-all duration-300">
                        <div class="h-32 overflow-hidden">
                            <img src="{{ $step['image'] }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" width="400" height="200">
                        </div>
                        <div class="p-5 relative">
                            <div class="absolute -top-5 right-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-accent-600 text-white text-body-sm font-medium shadow-elevated group-hover:scale-110 transition-transform duration-300">
                                {{ $step['step'] }}
                            </div>
                            <h3 class="font-medium text-stone-900 dark:text-white mb-2 break-words">{{ $step['title'] }}</h3>
                            <p class="text-body-sm text-stone-600 dark:text-stone-400 break-words">{{ $step['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- District Coverage Map --}}
    <section class="section section-alt" aria-labelledby="map-heading">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <p class="text-overline text-accent-600 mb-4">Our Footprint</p>
                <h2 id="map-heading" class="font-display text-display-md mb-6">Presence Across Every District</h2>
                <p class="text-body-lg text-stone-600 dark:text-stone-400">
                    Hover any zilla on the map to highlight it. We deliver architecture, engineering and construction consultancy from Kurigram and Rangpur — with reach across the country.
                </p>
            </div>

            <div class="mx-auto max-w-[820px]">
                <x-maps.bangladesh-map />

                <div class="mt-8 flex items-center justify-center gap-2 text-caption text-stone-500 dark:text-stone-400">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-accent-600" aria-hidden="true"></span>
                    Office locations — Kurigram &amp; Rangpur
                </div>
            </div>
        </div>
    </section>

    {{-- Offices --}}
    @if($offices->isNotEmpty())
        <section class="section" aria-labelledby="offices-heading">
            <div class="container">
                <div class="text-center mb-16">
                    <h2 id="offices-heading" class="font-display text-display-md mb-4">Our Locations</h2>
                    <p class="text-body-lg text-stone-600 dark:text-stone-400">Visit us at one of our offices</p>
                </div>

                <div class="grid-auto-fit-3 gap-8">
                    @foreach($offices as $office)
                        <x-ui.card variant="premium" class="p-8 h-full animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                            <div class="mb-5 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.14-7.5 11.25-7.5 11.25S4.5 17.64 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                </svg>
                            </div>

                            <h3 class="font-medium text-stone-900 dark:text-white mb-4">{{ $office->name }}</h3>

                            <address class="not-italic space-y-3 text-body-sm text-stone-600 dark:text-stone-400">
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
                        </x-ui.card>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Consultation CTA --}}
    <section class="section relative bg-accent-700 text-white overflow-hidden" aria-labelledby="cta-heading">
        {{-- Decorative gradient blobs --}}
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-40 text-accent-300 opacity-10">
            <x-illustrations.skyline class="h-full w-full" />
        </div>
        <div class="pointer-events-none absolute inset-0 blueprint-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -top-24 -right-20 h-72 w-72 rounded-full bg-accent-500/40 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-20 h-72 w-72 rounded-full bg-accent-900/60 blur-3xl" aria-hidden="true"></div>

        <div class="container relative text-center">
            <h2 id="cta-heading" class="font-display text-display-lg mb-6">Ready to Start Your Project?</h2>
            <p class="text-body-xl text-accent-100 max-w-2xl mx-auto mb-10">
                Let's discuss your vision. Our team of experts is ready to bring your ideas to life with precision and quality.
            </p>
            <a href="{{ route('consultation') }}" class="btn-secondary bg-white text-accent-700 hover:bg-accent-50 shadow-elevated">
                Start Your Project
            </a>
        </div>
    </section>
</x-layout.app>
