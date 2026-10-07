<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Office details">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="name" label="Office Name *" :value="old('name', $office->name)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="slug" label="URL Slug" :value="old('slug', $office->slug)" helper="Leave blank to generate it from the name." />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="address" label="Address" rows="3" :value="old('address', $office->address)" helper="Use separate lines for street, area and district." />
                </div>

                <div>
                    <x-ui.input type="tel" name="phone" label="Phone" :value="old('phone', $office->phone)" />
                </div>

                <div>
                    <x-ui.input type="email" name="email" label="Email" :value="old('email', $office->email)" />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="map_embed" label="Map Embed Code" rows="3" :value="old('map_embed', $office->map_embed)" helper="Optional iframe embed code from Google Maps." />
                </div>
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="visibility" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('visibility', $office->visibility ?? true))>
                    <span class="text-body-sm font-medium text-stone-900 dark:text-white">Show on the website</span>
                </label>

                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $office->locale)"
                    :placeholder="null"
                    required
                />

                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $office->sort_order ?? 0)" />
            </div>
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">
                    {{ $office->exists ? 'Save changes' : 'Create office' }}
                </button>
                <a href="{{ route('admin.offices.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
