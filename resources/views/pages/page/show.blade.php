<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema ?? []">
    <article>
        <header class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
            <div class="container py-16 md:py-20">
                <h1 class="font-display text-display-lg max-w-3xl">{{ $page->title }}</h1>

                @if($page->published_at)
                    <p class="mt-3 text-body-sm text-stone-500 dark:text-stone-400">
                        Last updated {{ $page->published_at->format('j M Y') }}
                    </p>
                @endif
            </div>
        </header>

        <div class="container py-12 md:py-16">
            <div class="prose prose-lg max-w-3xl">
                @forelse($page->content_blocks as $block)
                    @php
                        $type = $block['type'] ?? 'html';
                        $content = $block['content'] ?? '';
                    @endphp

                    @if($content === '' || $content === null)
                        @continue
                    @endif

                    @if($type === 'html')
                        {!! $content !!}
                    @elseif($type === 'heading')
                        <h2>{{ $block['text'] ?? strip_tags($content) }}</h2>
                    @elseif($type === 'text')
                        <p>{!! $content !!}</p>
                    @else
                        {!! $content !!}
                    @endif
                @empty
                    <p class="text-stone-500">This page has no content yet.</p>
                @endforelse
            </div>
        </div>
    </article>
</x-layout.app>
