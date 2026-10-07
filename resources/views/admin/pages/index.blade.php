@extends('layouts.admin')

@section('title', 'Pages')
@section('heading', 'Pages')

@section('actions')
    <a href="{{ route('admin.pages.create') }}" class="btn-primary btn-sm">New page</a>
@endsection

@section('content')
    <x-admin.panel>
        @if($pages->isEmpty())
            <p class="text-body-sm text-stone-500">No pages yet. Pages are reachable at <code>/{slug}</code>.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Page</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td class="px-4 py-3 font-medium text-stone-900 dark:text-white">{{ $page->title }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-500">/{{ $page->slug }}</td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :variant="$page->status === 'published' ? 'success' : 'secondary'">
                                        {{ ucfirst($page->status) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        @if($page->status === 'published')
                                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noopener" class="btn-ghost px-2 py-1 text-caption">View</a>
                                        @endif
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Move this page to trash?')">
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

    <div class="mt-6">{{ $pages->links() }}</div>
@endsection
