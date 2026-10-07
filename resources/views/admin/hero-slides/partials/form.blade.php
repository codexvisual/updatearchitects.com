<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <x-admin.panel title="Content">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="title" label="Title *" :value="old('title', $slide->title)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="eyebrow" label="Eyebrow" :value="old('eyebrow', $slide->eyebrow)" helper="Small kicker line shown above the title." />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="subtitle" label="Subtitle" rows="3" :value="old('subtitle', $slide->subtitle)" />
                </div>

                <div>
                    <x-ui.input name="cta_label" label="Primary button label" :value="old('cta_label', $slide->cta_label)" />
                </div>
                <div>
                    <x-ui.input name="cta_url" label="Primary button URL" :value="old('cta_url', $slide->cta_url)" placeholder="/projects" />
                </div>

                <div>
                    <x-ui.input name="secondary_cta_label" label="Secondary button label" :value="old('secondary_cta_label', $slide->secondary_cta_label)" />
                </div>
                <div>
                    <x-ui.input name="secondary_cta_url" label="Secondary button URL" :value="old('secondary_cta_url', $slide->secondary_cta_url)" placeholder="/consultation" />
                </div>
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="visibility" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('visibility', $slide->visibility ?? true))>
                    <span>
                        <span class="block text-body-sm font-medium text-stone-900 dark:text-white">Active on the website</span>
                        <span class="block text-caption text-stone-500 dark:text-stone-400">Uncheck to keep it hidden from the homepage hero.</span>
                    </span>
                </label>

                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $slide->locale)"
                    :placeholder="null"
                    required
                />

                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $slide->sort_order ?? 0)" />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Desktop image">
            @if($slide->featuredImage)
                <img src="{{ $slide->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $slide->title }}" class="mb-4 w-full rounded-lg" loading="lazy">
            @else
                <p class="mb-4 text-caption text-stone-500">No image attached.</p>
            @endif

            <x-forms.file-upload
                name="image"
                label="Replace image"
                helper="JPEG, PNG or WebP up to 5 MB. Recommended 1600x900+."
                :multiple="false"
                :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                :maxSizeMB="5"
            />
        </x-admin.panel>

        <x-admin.panel title="Mobile image">
            @if($slide->mobileImage)
                <img src="{{ $slide->mobileImage->getAvailableUrl(['large']) }}" alt="{{ $slide->title }}" class="mb-4 w-full rounded-lg" loading="lazy">
            @else
                <p class="mb-4 text-caption text-stone-500">No mobile image — desktop image is used on all screens.</p>
            @endif

            <x-forms.file-upload
                name="mobile_image"
                label="Replace mobile image"
                helper="Optional portrait/cropped image for small screens."
                :multiple="false"
                :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                :maxSizeMB="5"
            />
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">
                    {{ $slide->exists ? 'Save changes' : 'Create slide' }}
                </button>
                <a href="{{ route('admin.hero-slides.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
