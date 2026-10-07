@extends('layouts.admin')

@section('title', $tag->name)
@section('heading', $tag->name)

@section('actions')
    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Posts using this tag" :subtitle="$tag->posts_count.' posts'">
                @forelse($tag->posts()->latest('published_at')->limit(10)->get() as $post)
                    <a href="{{ route('admin.blog.edit', $post) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $post->title }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No posts use this tag yet.</p>
                @endforelse
            </x-admin.panel>
        </div>

        <div>
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $tag->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Language</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ strtoupper($tag->locale) }}</dd>
                    </div>
                </dl>
            </x-admin.panel>
        </div>
    </div>
@endsection
