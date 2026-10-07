@extends('layouts.admin')

@section('title', $service->name)
@section('heading', $service->name)

@section('actions')
    <a href="{{ route('services.show', $service->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View on site</a>
    <a href="{{ route('admin.services.edit', $service) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.panel title="Overview">
                @if($service->featuredImage)
                    <img src="{{ $service->featuredImage->getAvailableUrl(['large']) }}" alt="{{ $service->name }}" class="mb-5 w-full rounded-lg" loading="lazy">
                @endif

                @if($service->short_description)
                    <p class="mb-4 text-body text-stone-600 dark:text-stone-400">{{ $service->short_description }}</p>
                @endif

                @if($service->full_description)
                    <div class="prose max-w-none text-body">{!! $service->full_description !!}</div>
                @else
                    <p class="text-body-sm text-stone-500">No full description yet.</p>
                @endif
            </x-admin.panel>

            @if($service->children->isNotEmpty())
                <x-admin.panel title="Sub-services">
                    <ul class="space-y-2">
                        @foreach($service->children as $child)
                            <li>
                                <a href="{{ route('admin.services.edit', $child) }}" class="text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                                    {{ $child->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-admin.panel>
            @endif

            <x-admin.panel title="Linked projects" :subtitle="$service->projects->count().' projects'">
                @forelse($service->projects as $project)
                    <a href="{{ route('admin.projects.show', $project) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $project->title }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No projects linked yet.</p>
                @endforelse
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Category</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $service->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $service->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Language</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ strtoupper($service->locale) }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Visibility</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="$service->visibility ? 'success' : 'secondary'">
                                {{ $service->visibility ? 'Visible' : 'Hidden' }}
                            </x-ui.badge>
                        </dd>
                    </div>
                </dl>
            </x-admin.panel>

            <x-admin.panel title="Linked team">
                @forelse($service->teamMembers as $member)
                    <a href="{{ route('admin.team.edit', $member) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $member->name }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No team members linked.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Move this service to trash?')">
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
