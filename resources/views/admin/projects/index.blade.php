@php
    use Illuminate\Support\Str;

    $statusVariant = ['ongoing' => 'accent', 'completed' => 'success', 'upcoming' => 'secondary'];
@endphp
@extends('layouts.admin')

@section('title', 'Projects')
@section('heading', 'Projects')

@section('actions')
    <a href="{{ route('admin.projects.create') }}" class="btn-primary btn-sm">New project</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.projects.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search projects</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search by title or location…" class="input">
        </div>
        <div>
            <label for="status" class="sr-only">Status</label>
            <select id="status" name="status" class="input" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach(['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="category" class="sr-only">Category</label>
            <select id="category" name="category" class="input" onchange="this.form.submit()">
                <option value="">All categories</option>
                @foreach(\App\Models\ProjectCategory::orderBy('sort_order')->get() as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            @if(request()->hasAny(['search', 'status', 'category']))
                <a href="{{ route('admin.projects.index') }}" class="btn-ghost">Reset</a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="w-16">Cover</th>
                        <th scope="col">Project</th>
                        <th scope="col" class="hidden md:table-cell">Location</th>
                        <th scope="col">Status</th>
                        <th scope="col">Published</th>
                        <th scope="col" class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td class="px-4 py-3">
                                @if($project->featuredImage)
                                    <img src="{{ $project->featuredImage->getAvailableUrl(['thumbnail']) }}" alt="" class="h-10 w-16 rounded object-cover" loading="lazy">
                                @else
                                    <span class="inline-flex h-10 w-16 items-center justify-center rounded bg-stone-200 text-stone-400 dark:bg-stone-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6"/>
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $project->title }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">
                                    {{ Str::headline((string) $project->category) }}
                                    @if($project->area) · {{ $project->area }} @endif
                                </p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $project->location ?: '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$statusVariant[$project->status] ?? 'secondary'">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3 text-body-sm text-stone-500 dark:text-stone-400">
                                {{ $project->published_at?->format('M j, Y') ?: 'Draft' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Move this project to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-stone-500">No projects yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>
@endsection
