@extends('layouts.admin')

@section('title', 'Hero Slides')
@section('heading', 'Hero Slides')

@section('actions')
    <a href="{{ route('admin.hero-slides.create') }}" class="btn-primary btn-sm">New slide</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.hero-slides.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search slides</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search slides…" class="input">
        </div>
        <div>
            <label for="locale" class="sr-only">Language</label>
            <select id="locale" name="locale" class="input" onchange="this.form.submit()">
                <option value="">All languages</option>
                <option value="en" @selected(request('locale') === 'en')>English</option>
                <option value="bn" @selected(request('locale') === 'bn')>বাংলা (Bangla)</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Slide</th>
                        <th scope="col" class="hidden md:table-cell">Language</th>
                        <th scope="col" class="hidden md:table-cell">Order</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="w-36 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slides as $slide)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $slide->title }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">
                                    {{ \Illuminate\Support\Str::limit((string) $slide->subtitle, 80) ?: 'No subtitle' }}
                                </p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $slide->locale === 'bn' ? 'বাংলা' : 'English' }}
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $slide->sort_order }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$slide->visibility ? 'success' : 'secondary'">
                                    {{ $slide->visibility ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <form method="POST" action="{{ route('admin.hero-slides.toggle', $slide) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption {{ $slide->visibility ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                            {{ $slide->visibility ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" onsubmit="return confirm('Move this slide to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-stone-500">No hero slides yet. Add one to replace the default hero.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $slides->links() }}</div>
@endsection
