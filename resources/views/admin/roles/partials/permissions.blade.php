<x-admin.panel title="Permissions" subtitle="Grouped by resource.">
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($permissions as $group => $names)
            <fieldset>
                <legend class="text-overline mb-2 text-stone-400">{{ $group }}</legend>
                <div class="space-y-1.5">
                    @foreach($names as $permission)
                        <label class="flex items-center gap-2 text-body-sm text-stone-700 dark:text-stone-300">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission }}"
                                class="w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500"
                                @checked(in_array($permission, $selected, true))
                            >
                            {{ $permission }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>
</x-admin.panel>
