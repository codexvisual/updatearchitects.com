@extends('layouts.admin')

@section('title', $menu->name)
@section('heading', $menu->name)

@section('actions')
    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn-ghost btn-sm">Rename</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Menu items" subtitle="Drag order is set with the sort order field.">
                @if($menu->items->isEmpty())
                    <p class="text-body-sm text-stone-500">This menu has no items yet.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($menu->items as $item)
                            <li class="rounded-lg border border-stone-200 p-4 dark:border-stone-800">
                                <form method="POST" action="{{ route('admin.menus.items.update', [$menu, $item]) }}" class="grid gap-3 sm:grid-cols-[1fr_1fr_auto_auto] sm:items-end">
                                    @csrf
                                    @method('PUT')

                                    <x-ui.input name="title" label="Title" :value="$item->title" />
                                    <x-ui.input name="url" label="URL" :value="$item->url" />
                                    <x-ui.input type="number" min="0" name="sort_order" label="Order" :value="$item->sort_order" />
                                    <button type="submit" class="btn-primary btn-sm">Save</button>
                                </form>

                                <form method="POST" action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" class="mt-3 flex items-center gap-3">
                                    @csrf
                                    @method('DELETE')
                                    <label class="flex items-center gap-2 text-caption text-stone-500">
                                        <input type="checkbox" name="visibility" value="1" disabled @checked($item->visibility)>
                                        Visible
                                    </label>
                                    <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Remove item</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Add menu item">
                <form method="POST" action="{{ route('admin.menus.items.store', $menu) }}" class="space-y-4">
                    @csrf
                    <x-ui.input name="title" label="Title *" :value="old('title')" required />
                    <x-ui.input name="url" label="URL" :value="old('url')" placeholder="/about" />
                    <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $menu->items->count() + 1)" />
                    <button type="submit" class="btn-primary w-full">Add item</button>
                </form>
            </x-admin.panel>

            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $menu->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Language</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ strtoupper($menu->locale) }}</dd>
                    </div>
                </dl>
            </x-admin.panel>
        </div>
    </div>
@endsection
