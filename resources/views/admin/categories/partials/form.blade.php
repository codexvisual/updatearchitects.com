<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Category details">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.input name="name" label="Name *" :value="old('name', $category->name)" required />
                <x-ui.input name="slug" label="URL Slug" :value="old('slug', $category->slug)" helper="Leave blank to generate it from the name." />
                <div class="sm:col-span-2">
                    <x-ui.textarea name="description" label="Description" rows="3" :value="old('description', $category->description)" />
                </div>
                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $category->sort_order ?? 0)" />
                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $category->locale)"
                    :placeholder="null"
                    required
                />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
                <a href="{{ route($routeBase.'.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
