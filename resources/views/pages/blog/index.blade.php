@php
    use App\Models\Setting;

    $locale = app()->getLocale();
@endphp
<x-layout.app title="Insights & Blog" description="Architecture, engineering, construction, and interior design insights from Update Architects & Engineering." :canonicalUrl="route('blog')">
    {{-- Page header --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-20">
            <p class="text-overline text-accent-600 mb-4">Insights</p>
            <h1 class="font-display text-display-lg mb-4">Notes from the practice</h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl">
                {{ Setting::getValue('blog.heading_text', $locale, 'Short write-ups on design decisions, engineering detail and lessons from live sites.') }}
            </p>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        {{-- Filters --}}
        <form method="GET" action="{{ route('blog') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center mb-10">
            <div>
                <label for="post-search" class="sr-only">Search articles</label>
                <input id="post-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search articles…" class="input">
            </div>
            <div>
                <label for="post-category" class="sr-only">Filter by category</label>
                <select id="post-category" name="category" class="input" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                            {{ $category->name }} ({{ $category->posts_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
                @if(request()->hasAny(['search', 'category', 'tag']))
                    <a href="{{ route('blog') }}" class="btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="grid gap-8 sm:grid-cols-2">
                    @forelse($posts as $post)
                        <article>
                            <a href="{{ route('blog.show', $post->slug) }}" class="group block h-full animate-fade-in-up" style="animation-delay: {{ ($loop->index % 2) * 100 }}ms">
                                <x-ui.card variant="premium" class="h-full flex flex-col overflow-hidden">
                                    <div class="card-media relative">
                                        @if($post->featuredImage)
                                            <img src="{{ $post->featuredImage->getAvailableUrl(['large']) }}" srcset="{{ $post->featuredImage->responsiveSrcset() }}" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" alt="{{ $post->title }}" class="w-full aspect-[4/3] object-cover" loading="lazy" width="640" height="480">
                                        @else
                                            <div class="w-full aspect-[4/3]">
                                                <x-ui.placeholder ratio="video" icon="photo" class="h-full" />
                                            </div>
                                        @endif

                                        @if($post->category)
                                            <span class="card-chip absolute left-3 top-3">{{ $post->category->name }}</span>
                                        @endif
                                    </div>

                                    <div class="flex flex-1 flex-col p-6">
                                        @if($post->published_at)
                                            <p class="text-caption text-stone-500 dark:text-stone-400 mb-2">{{ $post->published_at->format('M d, Y') }}</p>
                                        @endif

                                        <h2 class="font-display text-heading-lg mb-2 group-hover:text-accent-600 transition-colors">{{ $post->title }}</h2>

                                        <p class="text-body-sm text-stone-600 dark:text-stone-400 flex-1">
                                            {{ $post->excerpt ?? Str::limit(strip_tags((string) $post->content), 150) }}
                                        </p>

                                        <span class="mt-5 inline-flex items-center gap-2 text-body-sm font-medium text-accent-600 transition-transform duration-300 group-hover:translate-x-1">
                                            Read article <span aria-hidden="true">→</span>
                                        </span>
                                    </div>
                                </x-ui.card>
                            </a>
                        </article>
                    @empty
                        <div class="sm:col-span-2">
                            <x-ui.placeholder ratio="wide" icon="photo" label="Articles will be published here shortly." class="border border-dashed border-stone-300 dark:border-stone-700" />
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-8">
                <div class="rounded-xl border border-stone-200 p-6 dark:border-stone-800">
                    <h2 class="font-display text-heading-md mb-4">Categories</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('blog') }}" @class([
                                'flex items-center justify-between rounded-lg px-3 py-2 text-body-sm transition-colors',
                                'bg-stone-100 font-medium text-stone-900 dark:bg-stone-800 dark:text-white' => ! request('category'),
                                'text-stone-600 hover:bg-stone-50 dark:text-stone-400 dark:hover:bg-stone-800' => request('category'),
                            ])>
                                <span>All articles</span>
                                <span class="text-stone-500">{{ $posts->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog', ['category' => $category->slug]) }}" @class([
                                    'flex items-center justify-between rounded-lg px-3 py-2 text-body-sm transition-colors',
                                    'bg-stone-100 font-medium text-stone-900 dark:bg-stone-800 dark:text-white' => request('category') === $category->slug,
                                    'text-stone-600 hover:bg-stone-50 dark:text-stone-400 dark:hover:bg-stone-800' => request('category') !== $category->slug,
                                ])>
                                    <span>{{ $category->name }}</span>
                                    <span class="text-stone-500">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($tags->isNotEmpty())
                    <div class="rounded-xl border border-stone-200 p-6 dark:border-stone-800">
                        <h2 class="font-display text-heading-md mb-4">Tags</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <a href="{{ route('blog', ['tag' => $tag->slug]) }}" class="inline-flex items-center rounded-full border border-stone-200 px-3 py-1 text-caption text-stone-600 hover:border-accent-400 hover:text-accent-700 dark:border-stone-700 dark:text-stone-300">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="rounded-xl bg-accent-700 p-6 text-white">
                    <h2 class="font-display text-heading-md mb-2">Have a project in mind?</h2>
                    <p class="text-body-sm text-accent-100 mb-6">Tell us about it and we will put together a clear scope.</p>
                    <a href="{{ route('consultation') }}" class="btn-secondary bg-white text-accent-700 hover:bg-accent-50 border-transparent w-full">
                        Start Your Project
                    </a>
                </div>
            </aside>
        </div>
    </div>
</x-layout.app>
