@extends('layouts.admin')

@section('title', $office->name)
@section('heading', $office->name)

@section('actions')
    <a href="{{ route('admin.offices.edit', $office) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Address">
                @if($office->address)
                    <address class="not-italic text-body text-stone-700 dark:text-stone-300">{!! nl2br(e($office->address)) !!}</address>
                @else
                    <p class="text-body-sm text-stone-500">No address recorded.</p>
                @endif

                <dl class="mt-6 space-y-3">
                    @if($office->phone)
                        <div>
                            <dt class="text-overline text-stone-400">Phone</dt>
                            <dd><a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}" class="text-body-sm text-accent-600">{{ $office->phone }}</a></dd>
                        </div>
                    @endif
                    @if($office->email)
                        <div>
                            <dt class="text-overline text-stone-400">Email</dt>
                            <dd><a href="mailto:{{ $office->email }}" class="break-all text-body-sm text-accent-600">{{ $office->email }}</a></dd>
                        </div>
                    @endif
                </dl>
            </x-admin.panel>

            <x-admin.panel title="Team members" class="mt-6">
                @forelse($office->teamMembers as $member)
                    <a href="{{ route('admin.team.edit', $member) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $member->name }}@if($member->designation) — {{ $member->designation }}@endif
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No team members assigned to this office.</p>
                @endforelse
            </x-admin.panel>
        </div>

        <div>
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $office->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Sort order</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $office->sort_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Visibility</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="$office->visibility ? 'success' : 'secondary'">
                                {{ $office->visibility ? 'Visible' : 'Hidden' }}
                            </x-ui.badge>
                        </dd>
                    </div>
                </dl>
            </x-admin.panel>
        </div>
    </div>
@endsection
