<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null">
    <div class="container py-16 max-w-3xl">
        <h1 class="font-display text-display-lg mb-8">Privacy Policy</h1>
        <div class="prose prose-lg max-w-none">
            {!! $content ?? '<p>Privacy policy content will be added through the CMS.</p>' !!}
        </div>
    </div>
</x-layout.app>