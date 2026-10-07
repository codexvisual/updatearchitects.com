@php
    $projectId = $project->id ?? $item->project_id;
@endphp
<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Stage details">
            <div class="space-y-5">
                <x-ui.input name="title" label="Stage Title *" :value="old('title', $item->title)" required />

                <x-ui.input name="slug" label="Slug" :value="old('slug', $item->slug)" helper="Leave blank to generate it from the title." />

                <x-ui.textarea name="description" label="Public Description" rows="4" :value="old('description', $item->description)" />

                <div class="grid gap-5 sm:grid-cols-3">
                    <x-ui.input type="date" name="date" label="Date" :value="old('date', $item->date?->toDateString())" />

                    <x-ui.select
                        name="status"
                        label="Status *"
                        :options="[
                            'pending' => 'Pending',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            'on_hold' => 'On Hold',
                            'not_applicable' => 'Not Applicable',
                        ]"
                        :value="old('status', $item->status)"
                        :placeholder="null"
                        required
                    />

                    <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $item->sort_order ?? 0)" />
                </div>

                <x-ui.textarea name="internal_notes" label="Internal Notes" rows="3" :value="old('internal_notes', $item->internal_notes)" helper="Only visible to the team in the admin." />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Options">
            <div class="space-y-4">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="is_completed" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('is_completed', $item->is_completed))>
                    <span>
                        <span class="block text-body-sm font-medium text-stone-900 dark:text-white">Mark as completed</span>
                        <span class="block text-caption text-stone-500 dark:text-stone-400">Shows a filled marker on the timeline.</span>
                    </span>
                </label>

                <label class="flex items-start gap-3">
                    <input type="checkbox" name="visibility" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('visibility', $item->visibility ?? true))>
                    <span>
                        <span class="block text-body-sm font-medium text-stone-900 dark:text-white">Show publicly</span>
                        <span class="block text-caption text-stone-500 dark:text-stone-400">Uncheck to hide this stage from visitors.</span>
                    </span>
                </label>
            </div>
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">
                    {{ $item->exists ? 'Save stage' : 'Add stage' }}
                </button>
                <a href="{{ route('admin.projects.progress.index', $projectId) }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
