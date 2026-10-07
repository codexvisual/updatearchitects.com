@php
    use Illuminate\Support\Str;

    $statusVariant = ['ongoing' => 'accent', 'completed' => 'success', 'upcoming' => 'secondary'];

    $facts = collect([
        $project->category ? ['label' => 'Category', 'value' => Str::headline($project->category)] : null,
        $project->status ? ['label' => 'Status', 'value' => ucfirst(str_replace('_', ' ', $project->status))] : null,
        $project->location ? ['label' => 'Location', 'value' => $project->location] : null,
        $project->area ? ['label' => 'Area', 'value' => $project->area] : null,
        $project->floors ? ['label' => 'Floors', 'value' => $project->floors] : null,
        $project->year ? ['label' => 'Year', 'value' => $project->year] : null,
        $project->consultant ? ['label' => 'Consultant', 'value' => $project->consultant] : null,
    ])->filter()->values();
@endphp
<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema">
    {{-- Hero --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-10 md:py-16">
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2 text-body-sm text-stone-500 dark:text-stone-400">
                    <li><a href="{{ route('projects') }}" class="hover:text-accent-600">Projects</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="text-stone-900 dark:text-stone-200 font-medium">{{ $project->title }}</li>
                </ol>
            </nav>

            <div class="flex flex-wrap gap-2 mb-5">
                @if($project->category)
                    <x-ui.badge variant="outline">{{ Str::headline($project->category) }}</x-ui.badge>
                @endif
                @if($project->status)
                    <x-ui.badge :variant="$statusVariant[$project->status] ?? 'secondary'">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-ui.badge>
                @endif
            </div>

            <h1 class="font-display text-display-lg max-w-3xl">{{ $project->title }}</h1>

            @if($project->summary)
                <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl mt-4">{{ $project->summary }}</p>
            @endif
        </div>
    </section>

    <div class="container py-12 md:py-16">
        @if($project->featuredImage)
            <img src="{{ $project->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $project->featuredImage->responsiveSrcset() }}" sizes="(min-width: 1280px) 1216px, 100vw" alt="{{ $project->title }}" class="w-full rounded-xl shadow-elevated mb-12" width="1200" height="675">
        @else
            <x-ui.placeholder ratio="wide" icon="photo" label="Cover image to be added" class="rounded-xl shadow-soft mb-12" />
        @endif

        @if($facts->isNotEmpty())
            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-12">
                @foreach($facts as $fact)
                    <div class="rounded-lg border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
                        <dt class="text-overline text-stone-400 mb-1">{{ $fact['label'] }}</dt>
                        <dd class="font-medium text-stone-900 dark:text-white">{{ $fact['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        @if($project->description)
            <div class="prose prose-lg max-w-none mb-12">
                {!! $project->description !!}
            </div>
        @endif

        {{-- Scope sections --}}
        @php
            $scopeSections = collect([
                ['title' => 'Design Concept', 'body' => $project->design_concept],
                ['title' => 'Architecture', 'body' => $project->architecture_info],
                ['title' => 'Structural Engineering', 'body' => $project->structural_info],
                ['title' => 'Engineering Services', 'body' => $project->engineering_info],
                ['title' => 'Geotechnical', 'body' => $project->geotechnical_info],
                ['title' => 'Construction', 'body' => $project->construction_info],
                ['title' => 'Interior', 'body' => $project->interior_info],
            ])->filter(fn($section) => filled($section['body']));
        @endphp

        @if($scopeSections->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-8">Project Scope</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($scopeSections as $section)
                        <div class="rounded-lg border border-stone-200 p-6 dark:border-stone-800">
                            <h3 class="font-medium text-stone-900 dark:text-white mb-2">{{ $section['title'] }}</h3>
                            <p class="text-body-sm text-stone-600 dark:text-stone-400">{{ $section['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Progress Timeline --}}
        @if($project->progressItems->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-8">Construction Progress</h2>
                <ol class="space-y-0">
                    @foreach($project->progressItems->sortBy('sort_order') as $item)
                        <li class="flex gap-5">
                            <div class="flex flex-col items-center pt-1.5">
                                <span @class([
                                    'h-3.5 w-3.5 rounded-full ring-4',
                                    'bg-accent-600 ring-accent-100 dark:ring-accent-900/40' => $item->is_completed,
                                    'bg-amber-500 ring-amber-100 dark:ring-amber-900/40' => ! $item->is_completed && $item->status === 'in_progress',
                                    'bg-stone-300 ring-stone-100 dark:bg-stone-700 dark:ring-stone-800' => ! $item->is_completed && $item->status !== 'in_progress',
                                ])></span>
                                @unless($loop->last)
                                    <span class="w-px flex-1 my-2 bg-stone-200 dark:bg-stone-800"></span>
                                @endunless
                            </div>
                            <div class="pb-8 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="font-medium text-stone-900 dark:text-white">{{ $item->title }}</p>
                                    <x-ui.badge :variant="$item->is_completed ? 'success' : ($item->status === 'in_progress' ? 'warning' : 'outline')">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </x-ui.badge>
                                </div>
                                @if($item->date)
                                    <p class="text-caption text-stone-500 mt-1">{{ $item->date->format('M d, Y') }}</p>
                                @endif
                                @if($item->description)
                                    <p class="text-body-sm text-stone-600 dark:text-stone-400 mt-2">{{ $item->description }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        {{-- Gallery --}}
        @if($project->images->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-8">Gallery</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($project->images as $image)
                        <figure>
                            <img src="{{ $image->media?->getAvailableUrl(['large']) }}" srcset="{{ $image->media?->responsiveSrcset() }}" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" alt="{{ $image->alt ?? $project->title }}" class="w-full aspect-video object-cover rounded-lg" loading="lazy">
                            @if($image->caption)
                                <figcaption class="text-caption text-stone-500 mt-2">{{ $image->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Team --}}
        @if($project->teamMembers->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-8">Project Team</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($project->teamMembers as $member)
                        <a href="{{ route('team.show', $member->slug) }}" class="flex items-center gap-4 rounded-lg border border-stone-200 p-4 dark:border-stone-800 hover:border-accent-300 dark:hover:border-accent-800 transition-colors">
                            <span class="text-accent-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <circle cx="12" cy="8" r="3.5"/>
                                    <path d="M4.5 20a7.5 7.5 0 0 1 15 0" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span>
                                <span class="block font-medium text-stone-900 dark:text-white">{{ $member->name }}</span>
                                <span class="block text-caption text-stone-500 dark:text-stone-400">{{ $member->pivot->role ?? $member->designation }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Related Services --}}
        @if($project->services->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-6">Services used on this project</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($project->services as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-4 py-2 text-body-sm text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-700 dark:text-stone-200">
                            {{ $service->name }}
                            <span aria-hidden="true">→</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- CTA --}}
        <div class="border-t border-stone-200 dark:border-stone-800 pt-12 text-center">
            <h2 class="font-display text-display-md mb-4">Planning a similar project?</h2>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-xl mx-auto mb-8">
                Share your requirements and our team will respond with a clear scope.
            </p>
            <a href="{{ route('consultation') }}" class="btn-primary">Start Your Project</a>
        </div>
    </div>
</x-layout.app>
