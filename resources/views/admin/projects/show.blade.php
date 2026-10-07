@php
    use Illuminate\Support\Str;

    $statusVariant = ['ongoing' => 'accent', 'completed' => 'success', 'upcoming' => 'secondary'];

    $facts = collect([
        $project->category ? ['Category', Str::headline($project->category)] : null,
        $project->status ? ['Status', ucfirst(str_replace('_', ' ', $project->status))] : null,
        $project->location ? ['Location', $project->location] : null,
        $project->area ? ['Area', $project->area] : null,
        $project->floors ? ['Floors', $project->floors] : null,
        $project->year ? ['Year', $project->year] : null,
        $project->client ? ['Client', $project->client] : null,
        $project->consultant ? ['Consultant', $project->consultant] : null,
        $project->published_at ? ['Published', $project->published_at->format('M j, Y')] : null,
    ])->filter()->values();
@endphp
@extends('layouts.admin')

@section('title', $project->title)
@section('heading', $project->title)

@section('actions')
    <a href="{{ route('projects.show', $project->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View on site</a>
    <a href="{{ route('admin.projects.progress.index', $project) }}" class="btn-secondary btn-sm">Progress</a>
    <a href="{{ route('admin.projects.edit', $project) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.panel title="Overview">
                @if($project->summary)
                    <p class="mb-4 text-body-sm text-stone-600 dark:text-stone-400">{{ $project->summary }}</p>
                @endif

                @if($project->description)
                    <div class="prose max-w-none text-body">{{ $project->description }}</div>
                @else
                    <p class="text-body-sm text-stone-500">No description yet.</p>
                @endif
            </x-admin.panel>

            <x-admin.panel title="Progress Items" :subtitle="$project->progressItems->count().' stages recorded'">
                @forelse($project->progressItems->sortBy('sort_order') as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-stone-200 py-3 last:border-0 dark:border-stone-800">
                        <div class="min-w-0">
                            <p class="text-body-sm font-medium text-stone-900 dark:text-white">{{ $item->title }}</p>
                            @if($item->date)
                                <p class="text-caption text-stone-500">{{ $item->date->format('M j, Y') }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-ui.badge :variant="$item->is_completed ? 'success' : ($item->status === 'in_progress' ? 'warning' : 'outline')">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </x-ui.badge>
                            <a href="{{ route('admin.progress.edit', $item) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="text-body-sm text-stone-500">No progress items yet.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel title="Gallery" :subtitle="$project->images->count().' images attached'">
                @forelse($project->images as $image)
                    <div class="mb-4 flex flex-col gap-3 rounded-lg border border-stone-200 p-3 last:mb-0 sm:flex-row sm:items-start dark:border-stone-800">
                        <img src="{{ $image->media?->getAvailableUrl(['thumbnail']) }}" alt="{{ $image->alt ?? $project->title }}" class="h-24 w-32 shrink-0 rounded object-cover" loading="lazy" width="128" height="96">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-caption font-medium text-stone-900 dark:text-white">{{ $image->media?->file_name }}</p>
                            @if($project->featured_image_id === $image->media_id)
                                <span class="mt-1 inline-block"><x-ui.badge variant="accent">Featured</x-ui.badge></span>
                            @elseif($image->caption)
                                <p class="mt-1 text-caption text-stone-500">{{ $image->caption }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" onsubmit="return confirm('Remove this image from the gallery?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-body-sm text-stone-500">No images attached yet. Upload them from the project edit screen or the media library.</p>
                @endforelse
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Details">
                <x-ui.badge :variant="$statusVariant[$project->status] ?? 'secondary'" class="mb-4">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </x-ui.badge>

                <dl class="space-y-3">
                    @foreach($facts as [$label, $value])
                        <div>
                            <dt class="text-overline text-stone-400">{{ $label }}</dt>
                            <dd class="text-body-sm text-stone-900 dark:text-white">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-admin.panel>

            <x-admin.panel title="Services">
                @forelse($project->services as $service)
                    <a href="{{ route('admin.services.edit', $service) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $service->name }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No services linked.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel title="Team">
                @forelse($project->teamMembers as $member)
                    <a href="{{ route('admin.team.edit', $member) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $member->name }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No team members linked.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Move this project to trash?')">
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
