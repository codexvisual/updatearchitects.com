@extends('layouts.admin')

@section('title', 'Menus')
@section('heading', 'Navigation Menus')

@section('actions')
    <a href="{{ route('admin.menus.create') }}" class="btn-primary btn-sm">New menu</a>
@endsection

@section('content')
    <x-admin.panel subtitle="The header uses the menu with the slug “main”. A menu with the slug “footer” is used by the footer when it exists.">
        @if($menus->isEmpty())
            <p class="text-body-sm text-stone-500">No menus yet.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Menu</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Items</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td class="px-4 py-3 font-medium text-stone-900 dark:text-white">{{ $menu->name }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-500">{{ $menu->slug }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-600 dark:text-stone-400">{{ $menu->items_count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.menus.show', $menu) }}" class="btn-ghost px-2 py-1 text-caption">Manage items</a>
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn-ghost px-2 py-1 text-caption">Rename</a>
                                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Delete this menu and all its items?')">
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

    <div class="mt-6">{{ $menus->links() }}</div>
@endsection
