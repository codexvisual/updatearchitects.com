<div class="max-w-2xl">
    <x-admin.panel title="Menu details">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-ui.input name="name" label="Menu Name *" :value="old('name', $menu->name)" required />
            <x-ui.input name="slug" label="Slug" :value="old('slug', $menu->slug)" helper="Use “main” for the header navigation." />
            <x-ui.select
                name="locale"
                label="Language *"
                :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                :value="old('locale', $menu->locale)"
                :placeholder="null"
                required
            />
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="btn-primary">{{ $menu->exists ? 'Save changes' : 'Create menu' }}</button>
            <a href="{{ route('admin.menus.index') }}" class="btn-ghost">Cancel</a>
        </div>
    </x-admin.panel>
</div>
