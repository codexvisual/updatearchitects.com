@extends('layouts.admin')

@section('title', 'Tags')
@section('heading', 'Tags')

@section('actions')
    <a href="{{ route('admin.tags.create') }}" class="btn-primary btn-sm">New tag</a>
@endsection

@section('content')
    <x-admin.panel>
        @if($tags->isEmpty())
            <p class="text-body-sm text-stone-500">No tags yet.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tag</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Posts</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            <tr>
                                <td class="px-4 py-3 font-medium text-stone-900 dark:text-white">{{ $tag->name }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-500">{{ $tag->slug }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-600 dark:text-stone-400">{{ $tag->posts_count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.tags.show', $tag) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Delete this tag?')">
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

    <div class="mt-6">{{ $tags->links() }}</div>
@endsection
