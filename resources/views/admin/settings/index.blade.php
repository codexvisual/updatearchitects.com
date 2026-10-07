@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Settings')
@section('heading', 'Site Settings')

@section('content')
    <form method="GET" action="{{ route('admin.settings.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search settings</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search by key or description…" class="input">
        </div>
        <div>
            <label for="group" class="sr-only">Group</label>
            <select id="group" name="group" class="input" onchange="this.form.submit()">
                <option value="">All groups</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}" @selected(request('group') === $group)>{{ Str::headline($group) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            <a href="{{ route('admin.settings.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @forelse($settings as $setting)
                <x-admin.panel>
                    <div class="grid gap-4 lg:grid-cols-[260px_1fr] lg:items-start">
                        <div>
                            <code class="block text-body-sm font-medium text-stone-900 dark:text-white">{{ $setting->key }}</code>
                            @if($setting->description)
                                <p class="mt-1 text-caption text-stone-500 dark:text-stone-400">{{ $setting->description }}</p>
                            @endif
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <x-ui.badge variant="outline">{{ $setting->group }}</x-ui.badge>
                                @if($setting->public)
                                    <x-ui.badge variant="accent">Public</x-ui.badge>
                                @endif
                            </div>
                        </div>

                        <div>
                            @php
                                $value = $setting->value;
                                $isLong = is_string($value) && strlen($value) > 90;
                            @endphp

                            @if($isLong)
                                <textarea
                                    name="settings[{{ $setting->key }}]"
                                    rows="3"
                                    class="input font-mono text-caption"
                                >{{ $value }}</textarea>
                            @elseif(is_array($value))
                                <textarea
                                    name="settings[{{ $setting->key }}]"
                                    rows="3"
                                    class="input font-mono text-caption"
                                >{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</textarea>
                            @else
                                <input
                                    type="text"
                                    name="settings[{{ $setting->key }}]"
                                    value="{{ is_scalar($value) ? $value : '' }}"
                                    class="input"
                                >
                            @endif
                        </div>
                    </div>
                </x-admin.panel>
            @empty
                <x-admin.panel>
                    <p class="text-body-sm text-stone-500">No settings match your filters.</p>
                </x-admin.panel>
            @endforelse
        </div>

        @if($settings->isNotEmpty())
            <div class="sticky bottom-0 mt-6 flex flex-col gap-3 rounded-xl border border-stone-200 bg-white/95 p-4 backdrop-blur sm:flex-row sm:items-center sm:justify-between dark:border-stone-800 dark:bg-stone-900/95">
                <p class="text-caption text-stone-500 dark:text-stone-400">Changes apply immediately across the site.</p>
                <button type="submit" class="btn-primary">Save settings</button>
            </div>
        @endif
    </form>

    <div class="mt-6">{{ $settings->links() }}</div>
@endsection
