<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema">
    {{-- Hero --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-10 md:py-16">
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2 text-body-sm text-stone-500 dark:text-stone-400">
                    <li><a href="{{ route('services') }}" class="hover:text-accent-600">Services</a></li>
                    @if($service->category)
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('services') }}" class="hover:text-accent-600">{{ $service->category->name }}</a></li>
                    @endif
                    <li aria-hidden="true">/</li>
                    <li class="text-stone-900 dark:text-stone-200 font-medium">{{ $service->name }}</li>
                </ol>
            </nav>

            <h1 class="font-display text-display-lg max-w-3xl">{{ $service->name }}</h1>

            @if($service->short_description)
                <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl mt-4">{{ $service->short_description }}</p>
            @endif

            <a href="{{ route('consultation') }}" class="btn-primary mt-8">Discuss This Service</a>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        @if($service->featuredImage)
            <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $service->name }}" class="w-full rounded-xl shadow-elevated mb-12" width="1200" height="675">
        @elseif($service->image_url)
            <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full rounded-xl shadow-elevated mb-12" width="1200" height="675">
        @endif

        @if($service->full_description)
            <div class="prose prose-lg max-w-none mb-12">
                {!! $service->full_description !!}
            </div>
        @endif

        @if($service->children->isNotEmpty())
            <section class="mb-12">
                <h2 class="font-display text-heading-xl mb-6">Scope of work</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($service->children as $child)
                        <a href="{{ route('services.show', $child->slug) }}" class="flex items-start gap-3 rounded-lg border border-stone-200 p-5 hover:border-accent-300 dark:border-stone-800 dark:hover:border-accent-800 transition-colors">
                            <svg class="h-5 w-5 shrink-0 mt-0.5 text-accent-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>
                                <span class="block font-medium text-stone-900 dark:text-white">{{ $child->name }}</span>
                                @if($child->short_description)
                                    <span class="block text-body-sm text-stone-600 dark:text-stone-400 mt-1">{{ $child->short_description }}</span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($service->faq)
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-6">Frequently Asked Questions</h2>
                <dl class="space-y-4">
                    @foreach($service->faq as $faq)
                        <div class="border border-stone-200 rounded-lg p-6 dark:border-stone-800">
                            <dt class="font-medium text-stone-900 dark:text-white">{{ $faq['question'] ?? $faq['q'] ?? '' }}</dt>
                            <dd class="text-body-sm text-stone-600 dark:text-stone-400 mt-2">{{ $faq['answer'] ?? $faq['a'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>
        @endif

        @if($service->projects->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-8">Related Projects</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($service->projects as $project)
                        <a href="{{ route('projects.show', $project->slug) }}" class="group">
                            @if($project->featuredImage)
                                <img src="{{ $project->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $project->title }}" class="w-full aspect-video object-cover rounded-lg mb-3" loading="lazy">
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

        @if($service->teamMembers->isNotEmpty())
            <section class="border-t border-stone-200 dark:border-stone-800 pt-12 mb-12">
                <h2 class="font-display text-heading-xl mb-6">Team</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($service->teamMembers as $member)
                        <a href="{{ route('team.show', $member->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-stone-200 px-4 py-2 text-body-sm text-stone-700 hover:border-accent-400 dark:border-stone-700 dark:text-stone-200">
                            {{ $member->name }}
                            @if($member->pivot->role ?? null)
                                <span class="text-stone-400">· {{ $member->pivot->role }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="border-t border-stone-200 dark:border-stone-800 pt-12 text-center">
            <h2 class="font-display text-display-md mb-4">Need help with this service?</h2>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-xl mx-auto mb-8">
                Send us your project details and we will respond with the right approach.
            </p>
            <a href="{{ route('consultation') }}" class="btn-primary">Start Your Project</a>
        </div>
    </div>
</x-layout.app>
