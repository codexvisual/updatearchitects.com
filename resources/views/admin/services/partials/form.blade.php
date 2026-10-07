<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <x-admin.panel title="Basics">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="name" label="Service Name *" :value="old('name', $service->name)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="slug" label="URL Slug" :value="old('slug', $service->slug)" helper="Leave blank to generate it from the name." />
                </div>

                <div>
                    <x-ui.select
                        name="category_id"
                        label="Category"
                        :options="$categories->pluck('name', 'id')->all()"
                        :value="old('category_id', $service->category_id)"
                        placeholder="Select a category"
                    />
                </div>

                <div>
                    <x-ui.select
                        name="parent_id"
                        label="Parent Service"
                        :options="$parents->pluck('name', 'id')->all()"
                        :value="old('parent_id', $service->parent_id)"
                        placeholder="None (top level)"
                    />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="short_description" label="Short Description" rows="2" :value="old('short_description', $service->short_description)" helper="Shown on listing cards and search results." />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="full_description" label="Full Description" rows="10" :value="old('full_description', $service->full_description)" />
                </div>
            </div>
        </x-admin.panel>

        <x-admin.panel title="SEO">
            <div class="space-y-5">
                <x-ui.input name="seo_title" label="Meta Title" :value="old('seo_title', $service->seo['title'] ?? null)" />
                <x-ui.textarea name="seo_description" label="Meta Description" rows="2" :value="old('seo_description', $service->seo['description'] ?? null)" />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="visibility" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('visibility', $service->visibility ?? true))>
                    <span>
                        <span class="block text-body-sm font-medium text-stone-900 dark:text-white">Visible on the website</span>
                        <span class="block text-caption text-stone-500 dark:text-stone-400">Uncheck to keep it hidden from visitors.</span>
                    </span>
                </label>

                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $service->locale)"
                    :placeholder="null"
                    required
                />

                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $service->sort_order ?? 0)" />

                <x-ui.input name="icon" label="Icon key" :value="old('icon', $service->icon)" helper="Optional identifier used by templates." />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Featured image">
            @if($service->featuredImage)
                <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $service->name }}" class="mb-4 w-full rounded-lg" loading="lazy">
            @else
                <p class="mb-4 text-caption text-stone-500">No image attached.</p>
            @endif

            <x-forms.file-upload
                name="image"
                label="Replace image"
                helper="JPEG, PNG or WebP up to 5 MB."
                :multiple="false"
                :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                :maxSizeMB="5"
            />
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">
                    {{ $service->exists ? 'Save changes' : 'Create service' }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
