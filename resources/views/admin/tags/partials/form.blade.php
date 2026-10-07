<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Tag details">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.input name="name" label="Tag Name *" :value="old('name', $tag->name)" required />
                <x-ui.input name="slug" label="URL Slug" :value="old('slug', $tag->slug)" helper="Leave blank to generate it from the name." />
                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $tag->locale)"
                    :placeholder="null"
                    required
                />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">{{ $tag->exists ? 'Save changes' : 'Create tag' }}</button>
                <a href="{{ route('admin.tags.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
