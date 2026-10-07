<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null" :schema="$schema">
    <article class="container py-16 max-w-3xl">
        <nav class="mb-8" aria-label="Breadcrumb">
            <a href="{{ route('blog') }}" class="text-body-sm text-stone-500 hover:text-accent-600">← Insights</a>
        </nav>

        <p class="text-caption text-accent-600 mb-4">{{ $post->category?->name }} • {{ $post->published_at?->format('M d, Y') }}</p>
        <h1 class="font-display text-display-lg mb-8">{{ $post->title }}</h1>

        @if($post->featuredImage)
            <img src="{{ $post->featuredImage->getUrl() }}" alt="{{ $post->title }}" class="w-full rounded-xl shadow-elevated mb-12">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $post->content !!}
        </div>

        @if($post->tagRelations->isNotEmpty())
            <div class="mt-12 flex flex-wrap gap-2">
                @foreach($post->tagRelations as $tag)
                    <a href="{{ route('blog', ['tag' => $tag->slug]) }}"><x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge></a>
                @endforeach
            </div>
        @endif
    </article>

    @if($relatedPosts->isNotEmpty())
        <section class="border-t border-stone-200 dark:border-stone-800 py-16">
            <div class="container max-w-3xl">
                <h2 class="font-display text-heading-xl mb-8">Related Articles</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="group">
                            <h3 class="font-medium group-hover:text-accent-600 transition-colors">{{ $related->title }}</h3>
                            <p class="text-caption text-stone-500">{{ $related->published_at?->format('M d, Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout.app>