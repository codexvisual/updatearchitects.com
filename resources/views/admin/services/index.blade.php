@extends('layouts.admin')

@section('title', 'Services')
@section('heading', 'Services')

@section('actions')
    <a href="{{ route('admin.services.create') }}" class="btn-primary btn-sm">New service</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.services.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search services</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search services…" class="input">
        </div>
        <div>
            <label for="category" class="sr-only">Category</label>
            <select id="category" name="category" class="input" onchange="this.form.submit()">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            <a href="{{ route('admin.services.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Service</th>
                        <th scope="col" class="hidden md:table-cell">Category</th>
                        <th scope="col">Visibility</th>
                        <th scope="col" class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.services.edit', $service) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $service->name }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">
                                    {{ \Illuminate\Support\Str::limit((string) $service->short_description, 80) ?: 'No summary' }}
                                </p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $service->category?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$service->visibility ? 'success' : 'secondary'">
                                    {{ $service->visibility ? 'Visible' : 'Hidden' }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.services.show', $service) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Move this service to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-12 text-center text-stone-500">No services yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $services->links() }}</div>
@endsection
