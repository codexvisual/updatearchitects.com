@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('actions')
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">View site</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Projects', 'value' => $stats['projects']['total'], 'hint' => $stats['projects']['ongoing'].' ongoing · '.$stats['projects']['completed'].' completed', 'route' => 'admin.projects.index'],
                ['label' => 'Services', 'value' => $stats['services'], 'hint' => 'Across all disciplines', 'route' => 'admin.services.index'],
                ['label' => 'Team members', 'value' => $stats['team'], 'hint' => $stats['offices'].' offices', 'route' => 'admin.team.index'],
                ['label' => 'Blog posts', 'value' => $stats['posts'], 'hint' => 'Published articles', 'route' => 'admin.blog.index'],
            ];
        @endphp

        @foreach($cards as $card)
            <a href="{{ route($card['route']) }}" class="group rounded-xl border border-stone-200 bg-white p-5 transition-all hover:-translate-y-0.5 hover:border-accent-300 dark:border-stone-800 dark:bg-stone-900 dark:hover:border-accent-700">
                <p class="text-overline text-stone-400">{{ $card['label'] }}</p>
                <p class="mt-2 font-display text-display-md text-stone-900 dark:text-white">{{ $card['value'] }}</p>
                <p class="mt-1 text-caption text-stone-500 dark:text-stone-400">{{ $card['hint'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Consultation leads --}}
        <div class="lg:col-span-2 space-y-6">
            <x-admin.panel title="Recent consultation leads" :subtitle="$stats['consultations']['open'].' open · '.$stats['consultations']['new'].' new'">
                <div class="flex justify-end">
                    <a href="{{ route('admin.consultations.index') }}" class="btn-link text-caption text-accent-600">View all →</a>
                </div>

                @forelse($recentLeads as $lead)
                    <a href="{{ route('admin.consultations.show', $lead) }}" class="flex items-center justify-between gap-4 border-b border-stone-200 py-3 last:border-0 dark:border-stone-800">
                        <span class="min-w-0">
                            <span class="block truncate text-body-sm font-medium text-stone-900 dark:text-white">{{ $lead->name }}</span>
                            <span class="block truncate text-caption text-stone-500 dark:text-stone-400">
                                {{ collect([$lead->project_type, $lead->project_location])->filter()->join(' · ') ?: 'No project details' }}
                            </span>
                        </span>
                        <span class="flex shrink-0 items-center gap-3">
                            <x-ui.badge :variant="match ($lead->status) {
                                'new' => 'accent',
                                'won' => 'success',
                                'lost' => 'secondary',
                                default => 'outline',
                            }">{{ ucfirst(str_replace('_', ' ', $lead->status)) }}</x-ui.badge>
                            <span class="hidden text-caption text-stone-400 sm:block">{{ $lead->created_at->diffForHumans() }}</span>
                        </span>
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No consultation leads yet. Submissions from the website appear here.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel title="Consultation leads" subtitle="New leads per month over the last six months.">
                <div class="flex h-40 items-end gap-3">
                    @foreach($chartData as $point)
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <span class="text-caption text-stone-500">{{ $point['total'] }}</span>
                            <div
                                class="w-full rounded-t bg-accent-600/80"
                                style="height: {{ max(4, round($point['total'] / $maxLeads * 100)) }}%"
                                title="{{ $point['label'] }}: {{ $point['total'] }}"
                            ></div>
                            <span class="text-caption text-stone-400">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </x-admin.panel>

            <x-admin.panel title="Recent projects">
                @if($recentProjects->isEmpty())
                    <p class="text-body-sm text-stone-500">No projects yet.</p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach($recentProjects as $project)
                            <a href="{{ route('admin.projects.show', $project) }}" class="flex items-center gap-4 rounded-lg border border-stone-200 p-3 transition-colors hover:border-accent-300 dark:border-stone-800 dark:hover:border-accent-700">
                                @if($project->featuredImage)
                                    <img src="{{ $project->featuredImage->getAvailableUrl(['thumbnail']) }}" alt="" class="h-12 w-16 shrink-0 rounded object-cover" loading="lazy">
                                @else
                                    <span class="inline-flex h-12 w-16 shrink-0 items-center justify-center rounded bg-stone-200 text-stone-400 dark:bg-stone-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6"/>
                                        </svg>
                                    </span>
                                @endif
                                <span class="min-w-0">
                                    <span class="block truncate text-body-sm font-medium text-stone-900 dark:text-white">{{ $project->title }}</span>
                                    <span class="block truncate text-caption text-stone-500 dark:text-stone-400">
                                        {{ Str::headline((string) $project->category) }} · {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </x-admin.panel>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <x-admin.panel title="Inbox">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.contacts.index') }}" class="rounded-lg border border-stone-200 p-4 transition-colors hover:border-accent-300 dark:border-stone-800 dark:hover:border-accent-700">
                        <p class="text-overline text-stone-400">Messages</p>
                        <p class="mt-1 font-display text-heading-lg text-stone-900 dark:text-white">{{ $stats['messages']['total'] }}</p>
                        <p class="text-caption text-stone-500 dark:text-stone-400">{{ $stats['messages']['unread'] }} unread</p>
                    </a>
                    <a href="{{ route('admin.consultations.index') }}" class="rounded-lg border border-stone-200 p-4 transition-colors hover:border-accent-300 dark:border-stone-800 dark:hover:border-accent-700">
                        <p class="text-overline text-stone-400">Leads</p>
                        <p class="mt-1 font-display text-heading-lg text-stone-900 dark:text-white">{{ $stats['consultations']['total'] }}</p>
                        <p class="text-caption text-stone-500 dark:text-stone-400">{{ $stats['consultations']['new'] }} new</p>
                    </a>
                </div>
            </x-admin.panel>

            <x-admin.panel title="Recent messages">
                @forelse($recentMessages as $message)
                    <a href="{{ route('admin.contacts.show', $message) }}" class="block border-b border-stone-200 py-3 last:border-0 dark:border-stone-800">
                        <span class="flex items-center justify-between gap-2">
                            <span class="truncate text-body-sm font-medium text-stone-900 dark:text-white">{{ $message->name }}</span>
                            @if($message->status === 'unread')
                                <span class="h-2 w-2 shrink-0 rounded-full bg-accent-600" aria-label="Unread"></span>
                            @endif
                        </span>
                        <span class="mt-0.5 block text-caption text-stone-500 dark:text-stone-400">{{ Str::limit($message->message, 70) }}</span>
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No messages yet.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel title="Recent activity">
                @forelse($recentActivity as $activity)
                    <div class="flex gap-3 border-b border-stone-200 py-3 last:border-0 dark:border-stone-800">
                        <x-ui.badge :variant="match ($activity->event) {
                            'created' => 'success',
                            'updated' => 'accent',
                            'deleted' => 'secondary',
                            default => 'outline',
                        }">{{ $activity->event }}</x-ui.badge>
                        <div class="min-w-0">
                            <p class="text-caption text-stone-600 dark:text-stone-400">
                                {{ class_basename($activity->subject_type ?? '') }} #{{ $activity->subject_id }}
                            </p>
                            <p class="text-caption text-stone-400">
                                {{ $activity->causer?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-body-sm text-stone-500">No activity recorded yet.</p>
                @endforelse
            </x-admin.panel>

            <x-admin.panel title="Quick links">
                <div class="grid gap-2">
                    <a href="{{ route('admin.projects.create') }}" class="rounded-lg border border-stone-200 px-4 py-2.5 text-body-sm text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-800 dark:text-stone-300">+ New project</a>
                    <a href="{{ route('admin.services.create') }}" class="rounded-lg border border-stone-200 px-4 py-2.5 text-body-sm text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-800 dark:text-stone-300">+ New service</a>
                    <a href="{{ route('admin.blog.create') }}" class="rounded-lg border border-stone-200 px-4 py-2.5 text-body-sm text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-800 dark:text-stone-300">+ New blog post</a>
                    <a href="{{ route('admin.settings.index') }}" class="rounded-lg border border-stone-200 px-4 py-2.5 text-body-sm text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-800 dark:text-stone-300">Site settings</a>
                </div>
            </x-admin.panel>
        </div>
    </div>
@endsection
