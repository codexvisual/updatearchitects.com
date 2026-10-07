<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Lead details">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.input name="name" label="Name *" :value="old('name', $lead->name)" required />
                <x-ui.input name="phone" label="Phone *" :value="old('phone', $lead->phone)" required />
                <x-ui.input type="email" name="email" label="Email *" :value="old('email', $lead->email)" required />
                <x-ui.input name="project_type" label="Project type" :value="old('project_type', $lead->project_type)" />
                <x-ui.input name="project_location" label="Project location" :value="old('project_location', $lead->project_location)" />
                <x-ui.input name="approximate_area" label="Approximate area" :value="old('approximate_area', $lead->approximate_area)" />
                <x-ui.input name="estimated_budget" label="Estimated budget" :value="old('estimated_budget', $lead->estimated_budget)" />
                <x-ui.input type="date" name="expected_start_date" label="Expected start" :value="old('expected_start_date', $lead->expected_start_date?->toDateString())" />
                <div class="sm:col-span-2">
                    <x-ui.textarea name="message" label="Message from the visitor" rows="5" :value="old('message', $lead->message)" />
                </div>
                <div class="sm:col-span-2">
                    <x-ui.textarea name="notes" label="Internal notes" rows="4" :value="old('notes', $lead->notes)" />
                </div>
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Pipeline">
            <div class="space-y-5">
                <x-ui.select
                    name="status"
                    label="Status *"
                    :options="collect($statuses)->mapWithKeys(fn ($status) => [$status => ucfirst(str_replace('_', ' ', $status))])->all()"
                    :value="old('status', $lead->status)"
                    :placeholder="null"
                    required
                />

                <x-ui.select
                    name="assigned_to"
                    label="Assigned to"
                    :options="$users->pluck('name', 'id')->all()"
                    :value="old('assigned_to', $lead->assigned_to)"
                    placeholder="Unassigned"
                />

                <x-ui.input type="datetime-local" name="follow_up_at" label="Follow up at" :value="old('follow_up_at', $lead->follow_up_at?->format('Y-m-d\TH:i'))" />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Services requested">
            @if(filled($lead->required_services))
                <div class="flex flex-wrap gap-2">
                    @foreach($lead->required_services as $service)
                        <x-ui.badge variant="outline">{{ \Illuminate\Support\Str::headline($service) }}</x-ui.badge>
                    @endforeach
                </div>
            @else
                <p class="text-body-sm text-stone-500">None recorded.</p>
            @endif
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">{{ $lead->exists ? 'Save lead' : 'Create lead' }}</button>
                <a href="{{ route('admin.consultations.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
