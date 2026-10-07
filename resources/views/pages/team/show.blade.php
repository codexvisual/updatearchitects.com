@php
    use Illuminate\Support\Str;

    $facts = collect([
        $member->designation ? ['label' => 'Designation', 'value' => $member->designation] : null,
        $member->qualification ? ['label' => 'Qualification', 'value' => $member->qualification] : null,
        $member->registration ? ['label' => 'Registration', 'value' => $member->registration] : null,
        $member->office?->name ? ['label' => 'Office', 'value' => $member->office->name] : null,
    ])->filter()->values();
@endphp
<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema">
    {{-- Hero --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-10 md:py-16">
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2 text-body-sm text-stone-500 dark:text-stone-400">
                    <li><a href="{{ route('team') }}" class="hover:text-accent-600">Team</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="text-stone-900 dark:text-stone-200 font-medium">{{ $member->name }}</li>
                </ol>
            </nav>

            <div class="grid gap-8 sm:grid-cols-[160px_1fr] sm:items-center">
                <x-ui.avatar :name="$member->name" :photo="$member->photo" size="xl" />

                <div>
                    <h1 class="font-display text-display-lg">{{ $member->name }}</h1>
                    @if($member->designation)
                        <p class="text-body-lg text-accent-600 mt-2">{{ $member->designation }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        <div class="grid gap-12 lg:grid-cols-[1fr_320px]">
            <div>
                @if($member->biography)
                    <section class="mb-12">
                        <h2 class="font-display text-heading-xl mb-4">Biography</h2>
                        <div class="prose">{!! $member->biography !!}</div>
                    </section>
                @endif

                @if($member->expertise)
                    <section class="mb-12">
                        <h2 class="font-display text-heading-xl mb-4">Expertise</h2>
                        <div class="prose text-body-lg">{{ $member->expertise }}</div>
                    </section>
                @endif

                @if($member->services->isNotEmpty())
                    <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                        <h2 class="font-display text-heading-xl mb-6">Services</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach($member->services as $service)
                                <a href="{{ route('services.show', $service->slug) }}" class="rounded-lg border border-stone-200 p-5 hover:border-accent-300 dark:border-stone-800 dark:hover:border-accent-800 transition-colors">
                                    <span class="block font-medium text-stone-900 dark:text-white">{{ $service->name }}</span>
                                    @if($service->short_description)
                                        <span class="block text-body-sm text-stone-600 dark:text-stone-400 mt-1">{{ $service->short_description }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($member->projects->isNotEmpty())
                    <section class="border-t border-stone-200 dark:border-stone-800 pt-12">
                        <h2 class="font-display text-heading-xl mb-8">Projects</h2>
                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach($member->projects as $project)
                                <a href="{{ route('projects.show', $project->slug) }}" class="group">
                                    @if($project->featuredImage)
                                        <img src="{{ $project->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $project->featuredImage->responsiveSrcset() }}" sizes="(min-width: 640px) 50vw, 100vw" alt="{{ $project->title }}" class="w-full aspect-video object-cover rounded-lg mb-3" loading="lazy">
                                    @else
                                        <x-ui.placeholder ratio="video" icon="photo" class="mb-3" />
                                    @endif
                                    <h3 class="font-medium text-stone-900 dark:text-white group-hover:text-accent-600 transition-colors">{{ $project->title }}</h3>
                                    @if($project->location)
                                        <p class="text-body-sm text-stone-500 dark:text-stone-400 mt-1">{{ $project->location }}</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside>
                @if($facts->isNotEmpty())
                    <dl class="rounded-xl border border-stone-200 p-6 dark:border-stone-800 space-y-4">
                        @foreach($facts as $fact)
                            <div>
                                <dt class="text-overline text-stone-400 mb-1">{{ $fact['label'] }}</dt>
                                <dd class="text-body-sm text-stone-900 dark:text-white">{{ $fact['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif

                <a href="{{ route('consultation') }}" class="btn-primary w-full mt-6">Discuss a project</a>
            </aside>
        </div>
    </div>
</x-layout.app>
