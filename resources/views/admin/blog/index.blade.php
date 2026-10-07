@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('heading', 'Blog Posts')

@section('actions')
    <a href="{{ route('admin.blog.create') }}" class="btn-primary btn-sm">New post</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.blog.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search posts</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search posts…" class="input">
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
            <a href="{{ route('admin.blog.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Post</th>
                        <th scope="col" class="hidden md:table-cell">Category</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="hidden lg:table-cell">Published</th>
                        <th scope="col" class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $post->title }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">
                                    {{ $post->author?->name ?? 'No author' }}
                                </p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $post->category?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="match ($post->status) {
                                    'published' => 'success',
                                    'scheduled' => 'warning',
                                    default => 'secondary',
                                }">
                                    {{ ucfirst($post->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-500 lg:table-cell">
                                {{ $post->published_at?->format('M j, Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.blog.show', $post) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Move this post to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-stone-500">No blog posts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $posts->links() }}</div>
@endsection
