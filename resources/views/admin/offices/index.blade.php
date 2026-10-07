@extends('layouts.admin')

@section('title', 'Offices')
@section('heading', 'Offices')

@section('actions')
    <a href="{{ route('admin.offices.create') }}" class="btn-primary btn-sm">New office</a>
@endsection

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($offices as $office)
            <x-admin.panel>
                <div class="flex items-start justify-between gap-3">
                    <h2 class="font-display text-heading-md text-stone-900 dark:text-white">{{ $office->name }}</h2>
                    <x-ui.badge :variant="$office->visibility ? 'success' : 'secondary'">
                        {{ $office->visibility ? 'Visible' : 'Hidden' }}
                    </x-ui.badge>
                </div>

                @if($office->address)
                    <address class="not-italic mt-3 text-body-sm text-stone-600 dark:text-stone-400">{!! nl2br(e($office->address)) !!}</address>
                @endif

                <div class="mt-3 space-y-1 text-body-sm">
                    @if($office->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}" class="block text-accent-600 hover:text-accent-700">{{ $office->phone }}</a>
                    @endif
                    @if($office->email)
                        <a href="mailto:{{ $office->email }}" class="block break-all text-stone-600 hover:text-accent-600 dark:text-stone-400">{{ $office->email }}</a>
                    @endif
                </div>

                <p class="mt-3 text-caption text-stone-500 dark:text-stone-400">
                    {{ $office->team_members_count }} team {{ \Illuminate\Support\Str::plural('member', $office->team_members_count) }}
                </p>

                <div class="mt-4 flex items-center gap-1">
                    <a href="{{ route('admin.offices.show', $office) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                    <a href="{{ route('admin.offices.edit', $office) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                    <form method="POST" action="{{ route('admin.offices.destroy', $office) }}" onsubmit="return confirm('Move this office to trash?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                    </form>
                </div>
            </x-admin.panel>
        @empty
            <x-admin.panel>
                <p class="text-body-sm text-stone-500">No offices yet.</p>
            </x-admin.panel>
        @endforelse
    </div>

    <div class="mt-6">{{ $offices->links() }}</div>
@endsection
