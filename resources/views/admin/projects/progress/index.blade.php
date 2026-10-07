@php
    $statusVariant = ['ongoing' => 'accent', 'completed' => 'success', 'upcoming' => 'secondary'];
@endphp
@extends('layouts.admin')

@section('title', 'Progress — '.$project->title)
@section('heading', 'Construction Progress')

@section('actions')
    <a href="{{ route('admin.projects.edit', $project) }}" class="btn-ghost btn-sm">{{ $project->title }}</a>
    <a href="{{ route('admin.projects.progress.create', $project) }}" class="btn-primary btn-sm">Add stage</a>
@endsection

@section('content')
    <x-admin.panel subtitle="Stages appear as a timeline on the public project page. Internal notes are never shown publicly.">
        @if($progressItems->isEmpty())
            <p class="text-body-sm text-stone-500">No progress stages yet.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="w-12">#</th>
                            <th scope="col">Stage</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progressItems as $item)
                            <tr>
                                <td class="px-4 py-3 text-caption text-stone-500">{{ $item->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-stone-900 dark:text-white">{{ $item->title }}</p>
                                    @if($item->description)
                                        <p class="text-caption text-stone-500">{{ \Illuminate\Support\Str::limit($item->description, 90) }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-body-sm text-stone-600 dark:text-stone-400">
                                    {{ $item->date?->format('M j, Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :variant="$item->is_completed ? 'success' : ($item->status === 'in_progress' ? 'warning' : 'outline')">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.progress.edit', $item) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                        <form method="POST" action="{{ route('admin.progress.destroy', $item) }}" onsubmit="return confirm('Remove this stage?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.panel>

    <div class="mt-6">{{ $progressItems->links() }}</div>
@endsection
