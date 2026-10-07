<div class="max-w-2xl">
    <x-admin.panel title="User details">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-ui.input name="name" label="Name *" :value="old('name', $user->name)" required />
            <x-ui.input type="email" name="email" label="Email *" :value="old('email', $user->email)" required />
            <x-ui.input type="password" name="password" label="Password" autocomplete="new-password" helper="Leave empty to keep the current password." />
            <x-ui.input type="password" name="password_confirmation" label="Confirm Password" autocomplete="new-password" />

            <div class="sm:col-span-2">
                <span class="label">Roles</span>
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 rounded-lg border border-stone-200 px-3 py-2 text-body-sm text-stone-700 dark:border-stone-700 dark:text-stone-300">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                class="w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500"
                                @checked(in_array($role->name, old('roles', $user->roles->pluck('name')->all()), false))
                            >
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="btn-primary">{{ $user->exists ? 'Save changes' : 'Create user' }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost">Cancel</a>
        </div>
    </x-admin.panel>
</div>
