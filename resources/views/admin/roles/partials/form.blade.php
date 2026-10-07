<div class="max-w-3xl space-y-6">
    <x-admin.panel title="Role details">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-ui.input name="name" label="Role Name *" :value="old('name', $role->name)" required />
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="btn-primary">{{ $role->exists ? 'Save changes' : 'Create role' }}</button>
            <a href="{{ route('admin.roles.index') }}" class="btn-ghost">Cancel</a>
        </div>
    </x-admin.panel>

    @include('admin.roles.partials.permissions')
</div>
