@php
    $facts = collect([
        $lead->phone ? ['Phone', $lead->phone] : null,
        $lead->email ? ['Email', $lead->email] : null,
        $lead->project_type ? ['Project type', \Illuminate\Support\Str::headline($lead->project_type)] : null,
        $lead->project_location ? ['Location', $lead->project_location] : null,
        $lead->approximate_area ? ['Approximate area', $lead->approximate_area] : null,
        $lead->estimated_budget ? ['Budget', $lead->estimated_budget] : null,
        $lead->expected_start_date ? ['Expected start', $lead->expected_start_date->format('M j, Y')] : null,
        $lead->contact_method ? ['Prefers', strtoupper($lead->contact_method)] : null,
        $lead->source ? ['Source', \Illuminate\Support\Str::headline($lead->source)] : null,
        $lead->created_at ? ['Received', $lead->created_at->format('M j, Y H:i')] : null,
    ])->filter()->values();
@endphp
@extends('layouts.admin')

@section('title', $lead->name)
@section('heading', $lead->name)

@section('actions')
    <a href="{{ route('admin.consultations.edit', $lead) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.panel title="Project brief">
                @if($lead->message)
                    <div class="prose max-w-none text-body">{{ $lead->message }}</div>
                @else
                    <p class="text-body-sm text-stone-500">No message provided.</p>
                @endif
            </x-admin.panel>

            @if(filled($lead->required_services))
                <x-admin.panel title="Services requested">
                    <div class="flex flex-wrap gap-2">
                        @foreach($lead->required_services as $service)
                            <x-ui.badge variant="outline">{{ \Illuminate\Support\Str::headline($service) }}</x-ui.badge>
                        @endforeach
                    </div>
                </x-admin.panel>
            @endif

            @if($lead->notes)
                <x-admin.panel title="Internal notes">
                    <div class="prose max-w-none text-body">{{ $lead->notes }}</div>
                </x-admin.panel>
            @endif

            <x-admin.panel title="Status history">
                @if($lead->statusHistory->isEmpty())
                    <p class="text-body-sm text-stone-500">No history recorded.</p>
                @else
                    <ol class="space-y-4">
                        @foreach($lead->statusHistory as $entry)
                            <li class="flex gap-3">
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-accent-600"></span>
                                <div>
                                    <p class="text-body-sm text-stone-900 dark:text-white">
                                        {{ $entry->from_status ? ucfirst(str_replace('_', ' ', $entry->from_status)).' → ' : '' }}{{ ucfirst(str_replace('_', ' ', $entry->to_status)) }}
                                    </p>
                                    <p class="text-caption text-stone-500">
                                        {{ $entry->created_at->format('M j, Y H:i') }}
                                        @if($entry->user) · {{ $entry->user->name }} @endif
                                    </p>
                                    @if($entry->note)
                                        <p class="mt-1 text-body-sm text-stone-600 dark:text-stone-400">{{ $entry->note }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Pipeline">
                <x-ui.badge :variant="match ($lead->status) {
                    'new' => 'accent',
                    'won' => 'success',
                    'lost' => 'secondary',
                    default => 'outline',
                }" class="mb-4">
                    {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                </x-ui.badge>

                <dl class="space-y-3">
                    @foreach($facts as [$label, $value])
                        <div>
                            <dt class="text-overline text-stone-400">{{ $label }}</dt>
                            <dd class="break-all text-body-sm text-stone-900 dark:text-white">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-admin.panel>

            <x-admin.panel title="Ownership">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Assigned to</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $lead->assignee?->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Follow up</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $lead->follow_up_at?->format('M j, Y H:i') ?? '—' }}</dd>
                    </div>
                </dl>
            </x-admin.panel>

            @if(filled($lead->files))
                <x-admin.panel title="Attachments">
                    <ul class="space-y-1">
                        @foreach($lead->files as $file)
                            <li class="text-body-sm text-stone-600 dark:text-stone-400">{{ basename($file) }}</li>
                        @endforeach
                    </ul>
                </x-admin.panel>
            @endif

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.consultations.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-outline w-full border-red-300 text-red-600 hover:bg-red-600 hover:text-white dark:border-red-900 dark:text-red-400">
                        Delete lead
                    </button>
                </form>
            </x-admin.panel>
        </div>
    </div>
@endsection
