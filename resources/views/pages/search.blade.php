<x-layout.app title="Search Results" description="Search results for your query." :noIndex="true">
    <div class="container py-16">
        <h1 class="font-display text-display-lg mb-8">Search Results</h1>

        <form method="GET" action="{{ route('search') }}" class="mb-12">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search projects, services, articles..." class="input max-w-xl text-body-lg py-4" autofocus>
        </form>

        @if($query)
            <p class="text-body-sm text-stone-500 mb-8">{{ $results->count() }} results for "{{ $query }}"</p>

            <div class="space-y-4">
                @forelse($results as $result)
                    <a href="{{ $result['url'] }}" class="group flex gap-5 rounded-xl border border-stone-200 bg-white p-5 shadow-soft transition-all duration-300 hover:-translate-y-1 hover:shadow-elevated hover:border-accent-300 dark:border-stone-800 dark:bg-stone-900 dark:hover:border-accent-800 animate-fade-in-up">
                        @if(! empty($result['image']))
                            <div class="card-media w-24 shrink-0 overflow-hidden rounded-lg sm:w-32">
                                <img src="{{ $result['image'] }}" alt="{{ $result['title'] }}" class="aspect-[4/3] w-full object-cover" width="128" height="96" loading="lazy">
                            </div>
                        @else
                            <div class="hidden shrink-0 items-center justify-center rounded-lg bg-accent-50 text-accent-600 sm:flex sm:w-32 dark:bg-accent-900/30 dark:text-accent-300">
                                <x-icons.icon :name="$result['type']" class="h-8 w-8" />
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <span class="card-chip mb-2 inline-block">{{ $result['type'] }}</span>
                            <h2 class="font-display text-heading-sm mb-1 group-hover:text-accent-600 transition-colors">{{ $result['title'] }}</h2>
                            <p class="text-body-sm text-stone-600 dark:text-stone-400 line-clamp-2">{{ $result['excerpt'] }}</p>
                        </div>

                        <span class="hidden shrink-0 self-center text-accent-600 transition-transform duration-300 group-hover:translate-x-1 sm:block" aria-hidden="true">→</span>
                    </a>
                @empty
                    <p class="text-stone-500">No results found for "{{ $query }}".</p>
                @endforelse
            </div>
        @endif
    </div>
</x-layout.app>