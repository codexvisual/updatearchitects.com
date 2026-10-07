@extends('layouts.admin')

@section('title', 'Consultation Leads')
@section('heading', 'Consultation Leads')

@section('actions')
    <a href="{{ route('admin.consultations.create') }}" class="btn-primary btn-sm">Add lead</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.consultations.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search leads</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone or location…" class="input">
        </div>
        <div>
            <label for="status" class="sr-only">Status</label>
            <select id="status" name="status" class="input" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach(['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'proposal_sent' => 'Proposal sent', 'won' => 'Won', 'lost' => 'Lost'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            <a href="{{ route('admin.consultations.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Lead</th>
                        <th scope="col" class="hidden md:table-cell">Project</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="hidden lg:table-cell">Received</th>
                        <th scope="col" class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.consultations.show', $lead) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $lead->name }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">
                                    {{ $lead->email }}@if($lead->phone) · {{ $lead->phone }}@endif
                                </p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ collect([$lead->project_type, $lead->project_location])->filter()->join(' · ') ?: '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="match ($lead->status) {
                                    'new' => 'accent',
                                    'won' => 'success',
                                    'lost' => 'secondary',
                                    default => 'outline',
                                }">
                                    {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                </x-ui.badge>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-500 lg:table-cell">
                                {{ $lead->created_at->format('M j, Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.consultations.show', $lead) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                    <a href="{{ route('admin.consultations.edit', $lead) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.consultations.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-stone-500">
                                No consultation leads yet. New submissions from the website appear here automatically.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $leads->links() }}</div>
@endsection
