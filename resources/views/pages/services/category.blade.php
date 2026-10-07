@php
    $locale = app()->getLocale();
    $description = $category->name.' services from '.config('app.name').'.';
    $canonical = url('/services/'.$category->slug);
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $category->name,
        'description' => $description,
        'url' => $canonical,
        'provider' => [
            '@type' => 'Organization',
            'name' => config('app.name'),
        ],
    ];
@endphp
<x-layout.app :title="$category->name" :description="$description" :canonicalUrl="$canonical" :schema="$schema">
    <div class="container py-12 md:py-16">
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-2 text-body-sm text-stone-500 dark:text-stone-400">
                <li><a href="{{ route('services') }}" class="hover:text-accent-600">Services</a></li>
                <li aria-hidden="true">/</li>
                <li class="text-stone-900 dark:text-stone-200 font-medium">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="font-display text-display-lg mb-4">{{ $category->name }}</h1>
        <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl mb-12">
            {{ $category->description ?? 'Discipline overview from '.config('app.name').'.' }}
        </p>

        @if($category->services->isEmpty())
            <div class="rounded-xl border border-dashed border-stone-300 p-10 text-center dark:border-stone-700">
                <p class="text-body-lg text-stone-600 dark:text-stone-400">Services in this discipline will be published soon.</p>
                <a href="{{ route('services') }}" class="btn-ghost btn-sm mt-4">Back to all services</a>
            </div>
        @else
            <div class="grid-auto-fit-3 gap-6">
                @foreach($category->services as $service)
                    <x-ui.card variant="premium" href="{{ route('services.show', $service->slug) }}" class="group p-6 h-full flex flex-col animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                        @if($service->featuredImage)
                            <div class="card-media mb-4 rounded-lg">
                                <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $service->name }}" class="w-full aspect-[4/3] object-cover" loading="lazy" width="600" height="450">
                            </div>
                        @elseif($service->image_url)
                            <div class="card-media mb-4 rounded-lg">
                                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full aspect-[4/3] object-cover" loading="lazy" width="600" height="450">
                            </div>
                        @else
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-900/30 dark:text-accent-300">
                                <x-icons.icon :name="$service->slug" class="h-6 w-6" />
                            </div>
                        @endif
                        <h2 class="font-display text-heading-lg mb-2">{{ $service->name }}</h2>
                        @if($service->short_description)
                            <p class="text-body-sm text-stone-600 dark:text-stone-400 flex-1">{{ $service->short_description }}</p>
                        @endif
                        <span class="mt-4 inline-flex items-center gap-2 text-caption font-medium text-accent-600 transition-transform duration-300 group-hover:translate-x-1">Learn more <span aria-hidden="true">→</span></span>
                    </x-ui.card>
                @endforeach
            </div>
        @endif

        <div class="mt-14 rounded-xl bg-stone-900 p-8 text-white dark:bg-stone-800 md:flex md:items-center md:justify-between md:gap-6">
            <div>
                <h2 class="font-display text-heading-lg text-white">Planning a project in {{ strtolower($category->name) }}?</h2>
                <p class="text-body-sm text-stone-300 mt-2">Tell us about your site, timeline and budget — we will come back with next steps.</p>
            </div>
            <a href="{{ route('consultation') }}" class="btn mt-5 bg-accent-600 text-white hover:bg-accent-500 md:mt-0">Start your project</a>
        </div>
    </div>
</x-layout.app>
