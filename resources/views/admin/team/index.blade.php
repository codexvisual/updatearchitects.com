@extends('layouts.admin')

@section('title', 'Team')
@section('heading', 'Team Members')

@section('actions')
    <a href="{{ route('admin.team.create') }}" class="btn-primary btn-sm">New member</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.team.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search team</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search by name or designation…" class="input">
        </div>
        <div>
            <label for="office" class="sr-only">Office</label>
            <select id="office" name="office" class="input" onchange="this.form.submit()">
                <option value="">All offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" @selected(request('office') == $office->id)>{{ $office->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            <a href="{{ route('admin.team.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="w-14">Photo</th>
                        <th scope="col">Member</th>
                        <th scope="col" class="hidden md:table-cell">Office</th>
                        <th scope="col">Visibility</th>
                        <th scope="col" class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($team as $member)
                        <tr>
                            <td class="px-4 py-3">
                                @if($member->photo)
                                    <img src="{{ $member->photo->getAvailableUrl(['thumbnail']) }}" alt="" class="h-10 w-10 rounded-full object-cover" loading="lazy">
                                @else
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-stone-200 text-stone-400 dark:bg-stone-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="12" cy="8" r="3.5"/>
                                            <path d="M4.5 20a7.5 7.5 0 0 1 15 0" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.team.edit', $member) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $member->name }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">
                                    {{ $member->designation ?: 'No designation' }}
                                </p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $member->office?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$member->visibility ? 'success' : 'secondary'">
                                    {{ $member->visibility ? 'Visible' : 'Hidden' }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.team.show', $member) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                    <a href="{{ route('admin.team.edit', $member) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.team.destroy', $member) }}" onsubmit="return confirm('Move this team member to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-stone-500">No team members yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $team->links() }}</div>
@endsection
