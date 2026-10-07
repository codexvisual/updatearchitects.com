@extends('layouts.admin')

@section('title', $post->title)
@section('heading', $post->title)

@section('actions')
    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View on site</a>
    <a href="{{ route('admin.blog.edit', $post) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Content">
                @if($post->featuredImage)
                    <img src="{{ $post->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $post->title }}" class="mb-6 w-full rounded-lg" loading="lazy">
                @endif

                @if($post->excerpt)
                    <p class="mb-5 text-body text-stone-600 dark:text-stone-400">{{ $post->excerpt }}</p>
                @endif

                @if($post->content)
                    <div class="prose max-w-none text-body">{!! $post->content !!}</div>
                @else
                    <p class="text-body-sm text-stone-500">No content yet.</p>
                @endif
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="match ($post->status) {
                                'published' => 'success',
                                'scheduled' => 'warning',
                                default => 'secondary',
                            }">
                                {{ ucfirst($post->status) }}
                            </x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Category</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $post->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Author</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $post->author?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Published</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $post->published_at?->format('M j, Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $post->slug }}</dd>
                    </div>
                </dl>
            </x-admin.panel>

            <x-admin.panel title="Tags">
                @forelse($post->tagRelations as $tag)
                    <a href="{{ route('admin.tags.edit', $tag) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $tag->name }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No tags.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Move this post to trash?')">
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
