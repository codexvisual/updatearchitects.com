@php
    $content = collect($page->content_blocks)->pluck('content')->filter()->implode("\n");
@endphp
@extends('layouts.admin')

@section('title', $page->title)
@section('heading', $page->title)

@section('actions')
    @if($page->status === 'published')
        <a href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View on site</a>
    @endif
    <a href="{{ route('admin.pages.edit', $page) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Content">
                @if($content)
                    <div class="prose max-w-none text-body">{!! $content !!}</div>
                @else
                    <p class="text-body-sm text-stone-500">This page has no content yet.</p>
                @endif
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">/{{ $page->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="$page->status === 'published' ? 'success' : 'secondary'">
                                {{ ucfirst($page->status) }}
                            </x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Language</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ strtoupper($page->locale) }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Published</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $page->published_at?->format('M j, Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </x-admin.panel>

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Move this page to trash?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-outline w-full border-red-300 text-red-600 hover:bg-red-600 hover:text-white dark:border-red-900 dark:text-red-400">
                        Move to trash
                    </button>
                </form>
            </x-admin.panel>
        </div>
    </div>
@endsection
