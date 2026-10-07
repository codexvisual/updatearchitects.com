@php
    $content = collect($page->content_blocks)->pluck('content')->filter()->implode("\n");
@endphp
<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Page content">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="title" label="Title *" :value="old('title', $page->title)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="slug" label="URL Slug" :value="old('slug', $page->slug)" helper="The page will be available at /{slug}." />
                </div>

                <div class="sm:col-span-2">
                    <label for="content" class="label">Content</label>
                    <textarea id="content" name="content" rows="18" class="input font-mono text-caption">{{ old('content', $content) }}</textarea>
                    <p class="helper-text">Basic HTML is supported, e.g. &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;table&gt;.</p>
                </div>
            </div>
        </x-admin.panel>

        <x-admin.panel title="SEO" class="mt-6">
            <div class="space-y-5">
                <x-ui.input name="seo_title" label="Meta Title" :value="old('seo_title', $page->seo['title'] ?? null)" />
                <x-ui.textarea name="seo_description" label="Meta Description" rows="2" :value="old('seo_description', $page->seo['description'] ?? null)" />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <x-ui.select
                    name="status"
                    label="Status *"
                    :options="['draft' => 'Draft', 'published' => 'Published']"
                    :value="old('status', $page->status)"
                    :placeholder="null"
                    required
                />

                <x-ui.input type="datetime-local" name="published_at" label="Publish date" :value="old('published_at', $page->published_at?->format('Y-m-d\TH:i'))" />

                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $page->locale)"
                    :placeholder="null"
                    required
                />

                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $page->sort_order ?? 0)" />
            </div>
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">{{ $page->exists ? 'Save changes' : 'Create page' }}</button>
                <a href="{{ route('admin.pages.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
