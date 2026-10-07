@extends('layouts.admin')

@section('title', $member->name)
@section('heading', $member->name)

@section('actions')
    <a href="{{ route('team.show', $member->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View on site</a>
    <a href="{{ route('admin.team.edit', $member) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.panel title="Profile">
                <div class="flex flex-col gap-6 sm:flex-row">
                    @if($member->photo)
                        <img src="{{ $member->photo->getAvailableUrl(['thumbnail']) }}" alt="{{ $member->name }}" class="h-32 w-32 shrink-0 rounded-full object-cover" loading="lazy">
                    @endif

                    <div>
                        @if($member->designation)
                            <p class="text-body-lg text-accent-600">{{ $member->designation }}</p>
                        @endif

                        <dl class="mt-4 space-y-3">
                            @if($member->qualification)
                                <div>
                                    <dt class="text-overline text-stone-400">Qualification</dt>
                                    <dd class="text-body-sm text-stone-700 dark:text-stone-300">{{ $member->qualification }}</dd>
                                </div>
                            @endif
                            @if($member->expertise)
                                <div>
                                    <dt class="text-overline text-stone-400">Expertise</dt>
                                    <dd class="text-body-sm text-stone-700 dark:text-stone-300">{{ $member->expertise }}</dd>
                                </div>
                            @endif
                            @if($member->registration)
                                <div>
                                    <dt class="text-overline text-stone-400">Registration</dt>
                                    <dd class="text-body-sm text-stone-700 dark:text-stone-300">{{ $member->registration }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                @if($member->biography)
                    <div class="prose mt-6 max-w-none border-t border-stone-200 pt-6 text-body dark:border-stone-800">
                        {!! $member->biography !!}
                    </div>
                @endif
            </x-admin.panel>

            <x-admin.panel title="Projects" :subtitle="$member->projects->count().' linked'">
                @forelse($member->projects as $project)
                    <a href="{{ route('admin.projects.show', $project) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $project->title }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No projects linked.</p>
                @endforelse
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Office</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $member->office?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Email</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $member->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Phone</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $member->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $member->slug }}</dd>
                    </div>
                </dl>
            </x-admin.panel>

            <x-admin.panel title="Services" :subtitle="$member->services->count().' linked'">
                @forelse($member->services as $service)
                    <a href="{{ route('admin.services.edit', $service) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $service->name }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No services linked.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.team.destroy', $member) }}" onsubmit="return confirm('Move this team member to trash?')">
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
