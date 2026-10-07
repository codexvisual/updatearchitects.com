@php
    use App\Models\Setting;

    $locale = app()->getLocale();
@endphp
<x-layout.app title="Our Services" description="Architecture, structural and geotechnical engineering, construction consultancy, electrical design and interior design services." :canonicalUrl="route('services')">
    {{-- Page header --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-20">
            <p class="text-overline text-accent-600 mb-4">Our Services</p>
            <h1 class="font-display text-display-lg mb-4">Design & Engineering Under One Roof</h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl">
                {{ Setting::getValue('services.heading_text', $locale, 'From feasibility study to handover, our in-house disciplines cover architecture, structural and geotechnical engineering, construction supervision, electrical design and interior design.') }}
            </p>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        @php
            $allServices = $categories->flatMap(fn($category) => $category->services)->values();
        @endphp

        @if($allServices->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($allServices as $service)
                    <x-ui.card variant="premium" href="{{ route('services.show', $service->slug) }}" class="group flex h-full flex-col p-7 animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                        @if($service->featuredImage)
                            <div class="card-media mb-6 overflow-hidden rounded-lg">
                                <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $service->name }}" class="aspect-[4/3] w-full object-cover" width="600" height="450" loading="lazy">
                            </div>
                        @elseif($service->image_url)
                            <div class="card-media mb-6 overflow-hidden rounded-lg">
                                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="aspect-[4/3] w-full object-cover" width="600" height="450" loading="lazy">
                            </div>
                        @else
                            <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300">
                                <x-icons.icon :name="$service->slug" class="h-6 w-6" />
                            </div>
                        @endif

                        @if($service->category)
                            <span class="card-chip mb-4 self-start">
                                {{ $service->category->name }}
                            </span>
                        @endif

                        <h2 class="font-display text-heading-lg mb-3 group-hover:text-accent-600 transition-colors">{{ $service->name }}</h2>

                        @if($service->short_description)
                            <p class="text-body-sm text-stone-600 dark:text-stone-400 flex-1">{{ $service->short_description }}</p>
                        @endif

                        <span class="mt-6 inline-flex items-center gap-2 text-body-sm font-medium text-accent-600 transition-transform duration-300 group-hover:translate-x-1">
                            Learn more <span aria-hidden="true">→</span>
                        </span>
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <x-ui.placeholder ratio="wide" icon="building" label="Services will be published here shortly." class="border border-dashed border-stone-300 dark:border-stone-700" />
        @endif

        {{-- CTA --}}
        <div class="mt-16 rounded-xl bg-accent-700 px-8 py-12 text-center text-white">
            <h2 class="font-display text-display-md mb-4">Need a specific service?</h2>
            <p class="text-body-lg text-accent-100 max-w-2xl mx-auto mb-8">
                Tell us about your project and we will put together the right team and scope for it.
            </p>
            <a href="{{ route('consultation') }}" class="btn-secondary bg-white text-accent-700 hover:bg-accent-50 border-transparent shadow-elevated">
                Start Your Project
            </a>
        </div>
    </div>
</x-layout.app>
