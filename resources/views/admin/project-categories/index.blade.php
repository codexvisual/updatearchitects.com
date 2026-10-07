@php
    use App\Models\Project;
@endphp
@extends('layouts.admin')

@section('title', 'Project Categories')
@section('heading', 'Project Categories')

@section('actions')
    <a href="{{ route('admin.project-categories.create') }}" class="btn-primary btn-sm">New category</a>
@endsection

@section('content')
    <x-admin.panel>
        @if($categories->isEmpty())
            <p class="text-body-sm text-stone-500">No project categories yet.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Projects</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="px-4 py-3 font-medium text-stone-900 dark:text-white">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-500">{{ $category->slug }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-600 dark:text-stone-400">{{ $category->projects_count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.project-categories.show', $category) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                        <a href="{{ route('admin.project-categories.edit', $category) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                        <form method="POST" action="{{ route('admin.project-categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
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

    <div class="mt-6">{{ $categories->links() }}</div>
@endsection
