@php
    use App\Models\Setting;
    use Illuminate\Support\Str;

    $locale = app()->getLocale();
@endphp
<x-layout.app title="Projects" description="Architecture, engineering, and construction projects by Update Architects & Engineering." :canonicalUrl="route('projects')">
    {{-- Page header --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-20">
            <p class="text-overline text-accent-600 mb-4">Portfolio</p>
            <h1 class="font-display text-display-lg mb-4">Our Projects</h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl">
                {{ Setting::getValue('projects.heading_text', $locale, 'Browse the residential, commercial and institutional projects delivered by our design and engineering teams.') }}
            </p>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        {{-- Filters --}}
        {{--
            Mobile-first: a single stacked column would push four full-width rows
            (~250px) above the first project. Two columns let the two selects sit
            side by side, so search / filters / actions collapse to three rows.
            The explicit col-span overrides keep the single-row desktop bar intact.
        --}}
        <form method="GET" action="{{ route('projects') }}" class="mb-6 grid grid-cols-2 gap-3 sm:mb-10 lg:grid-cols-[minmax(0,1fr)_auto_auto_auto] lg:items-center">
            <div class="col-span-2 lg:col-span-1">
                <label for="project-search" class="sr-only">Search projects</label>
                <input id="project-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search by name, location or description…" class="input">
            </div>
            <div>
                <label for="project-category" class="sr-only">Filter by category</label>
                <select id="project-category" name="category" class="input" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="project-status" class="sr-only">Filter by status</label>
                <select id="project-status" name="status" class="input" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2 flex gap-3 lg:col-span-1">
                <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('projects') }}" class="btn-ghost flex-1 lg:flex-none">Reset</a>
                @endif
            </div>
        </form>

        <p class="text-body-sm text-stone-500 dark:text-stone-400 mb-6 sm:mb-8" role="status">
            {{ $projects->total() }} {{ Str::plural('project', $projects->total()) }} found
        </p>

        {{--
            Two columns from the smallest phone up (previously one, which made
            each card fill the whole viewport). lg/xl keep the original 3/4
            column desktop layout that `.grid-auto-fit` provides elsewhere.
        --}}
        <div class="grid grid-cols-2 gap-4 sm:gap-6 md:gap-8 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group block h-full animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                    <x-ui.card variant="premium" class="h-full flex flex-col overflow-hidden">
                        <div class="card-media relative">
                            @if($project->featuredImage)
                                <img src="{{ $project->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $project->featuredImage->responsiveSrcset() }}" sizes="(min-width: 1280px) 25vw, (min-width: 1024px) 33vw, (min-width: 640px) 50vw, 50vw" alt="{{ $project->title }}" class="w-full aspect-[4/3] object-cover" loading="lazy" width="640" height="480">
                            @else
                                <div class="w-full aspect-[4/3]">
                                    <x-ui.placeholder ratio="video" icon="photo" class="h-full" />
                                </div>
                            @endif

                            @if($project->status)
                                <span class="card-chip absolute right-2 top-2 sm:right-3 sm:top-3">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col p-3 sm:p-5 md:p-6">
                            @if($project->category)
                                <div class="mb-2 sm:mb-3">
                                    <x-ui.badge variant="outline">{{ Str::headline($project->category) }}</x-ui.badge>
                                </div>
                            @endif

                            <h2 class="font-display text-heading-sm sm:text-heading-md mb-1.5 sm:mb-2 group-hover:text-accent-600 transition-colors">{{ $project->title }}</h2>

                            @if($project->location)
                                <p class="text-caption uppercase tracking-wide text-stone-500 dark:text-stone-400 mb-2 line-clamp-2 sm:mb-3 sm:line-clamp-none">{{ $project->location }}</p>
                            @endif

                            <p class="text-body-sm text-stone-600 dark:text-stone-400 line-clamp-2 sm:flex-1 sm:line-clamp-none">
                                {{ $project->summary ?? Str::limit((string) $project->description, 130) }}
                            </p>

                            <span class="mt-3 inline-flex items-center gap-2 text-caption font-medium text-accent-600 transition-transform duration-300 group-hover:translate-x-1 sm:mt-5 sm:text-body-sm">
                                View project <span aria-hidden="true">→</span>
                            </span>
                        </div>
                    </x-ui.card>
                </a>
            @empty
                <div class="col-span-full">
                    <x-ui.placeholder ratio="wide" icon="photo" label="No projects match your filters yet." class="border border-dashed border-stone-300 dark:border-stone-700" />
                </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $projects->links() }}</div>
    </div>
</x-layout.app>
