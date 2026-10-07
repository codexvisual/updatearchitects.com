<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <x-admin.panel title="Post">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="title" label="Title *" :value="old('title', $post->title)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="slug" label="URL Slug" :value="old('slug', $post->slug)" helper="Leave blank to generate it from the title." />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="excerpt" label="Excerpt" rows="3" :value="old('excerpt', $post->excerpt)" helper="Shown on listing cards. Falls back to the start of the content." />
                </div>

                <div class="sm:col-span-2">
                    <label for="content" class="label">
                        Content *
                        <span class="text-accent-600 ml-1" aria-hidden="true">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="16"
                        required
                        class="input font-mono text-caption"
                    >{{ old('content', $post->content) }}</textarea>
                    <p class="helper-text">Basic HTML is supported, e.g. &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;blockquote&gt;.</p>
                </div>
            </div>
        </x-admin.panel>

        <x-admin.panel title="SEO">
            <div class="space-y-5">
                <x-ui.input name="seo_title" label="Meta Title" :value="old('seo_title', $post->seo['title'] ?? null)" />
                <x-ui.textarea name="seo_description" label="Meta Description" rows="2" :value="old('seo_description', $post->seo['description'] ?? null)" />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <x-ui.select
                    name="status"
                    label="Status *"
                    :options="['draft' => 'Draft', 'scheduled' => 'Scheduled', 'published' => 'Published']"
                    :value="old('status', $post->status)"
                    :placeholder="null"
                    required
                />

                <x-ui.input
                    type="datetime-local"
                    name="published_at"
                    label="Publish date &amp; time"
                    :value="old('published_at', $post->published_at?->format('Y-m-d\TH:i'))"
                />

                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $post->locale)"
                    :placeholder="null"
                    required
                />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Organisation">
            <div class="space-y-5">
                <x-ui.select
                    name="category_id"
                    label="Category"
                    :options="$categories->pluck('name', 'id')->all()"
                    :value="old('category_id', $post->category_id)"
                    placeholder="Select a category"
                />

                <fieldset>
                    <legend class="label">Tags</legend>
                    <div class="space-y-1.5">
                        @forelse($tags as $tag)
                            <label class="flex items-center gap-2 text-body-sm text-stone-700 dark:text-stone-300">
                                <input
                                    type="checkbox"
                                    name="tags[]"
                                    value="{{ $tag->id }}"
                                    class="w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500"
                                    @checked(in_array($tag->id, old('tags', $post->tagRelations->pluck('id')->all()), false))
                                >
                                {{ $tag->name }}
                            </label>
                        @empty
                            <p class="text-body-sm text-stone-500">No tags created yet.</p>
                        @endforelse
                    </div>
                </fieldset>
            </div>
        </x-admin.panel>

        <x-admin.panel title="Featured image">
            @if($post->featuredImage)
                <img src="{{ $post->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $post->title }}" class="mb-4 w-full rounded-lg" loading="lazy">
            @else
                <p class="mb-4 text-caption text-stone-500">No image attached.</p>
            @endif

            <x-forms.file-upload
                name="image"
                label="Upload image"
                helper="JPEG, PNG or WebP up to 5 MB."
                :multiple="false"
                :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                :maxSizeMB="5"
            />
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">{{ $post->exists ? 'Save changes' : 'Create post' }}</button>
                <a href="{{ route('admin.blog.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
