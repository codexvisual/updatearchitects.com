@extends('layouts.admin')

@section('title', 'Activity Log')
@section('heading', 'Activity Log')

@section('content')
    <x-admin.panel subtitle="Changes recorded by editors and administrators.">
        @if($logs->isEmpty())
            <p class="text-body-sm text-stone-500">No activity recorded yet.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">Action</th>
                            <th scope="col" class="hidden md:table-cell">Record</th>
                            <th scope="col">Changes</th>
                            <th scope="col" class="text-right">When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="px-4 py-3 text-body-sm text-stone-900 dark:text-white">
                                    {{ $log->causer?->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :variant="$log->event === 'deleted' ? 'secondary' : ($log->event === 'created' ? 'success' : ($log->event === 'updated' ? 'accent' : 'outline'))">
                                        {{ $log->event }}
                                    </x-ui.badge>
                                </td>
                                <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                    {{ class_basename($log->subject_type ?? '') }} #{{ $log->subject_id ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php $changes = collect($log->properties?->get('attributes') ?? []); @endphp
                                    @if($changes->isEmpty())
                                        <span class="text-caption text-stone-400">—</span>
                                    @else
                                        <ul class="space-y-0.5 text-caption text-stone-500 dark:text-stone-400">
                                            @foreach($changes->keys()->take(4) as $field)
                                                <li>{{ $field }}</li>
                                            @endforeach
                                            @if($changes->count() > 4)
                                                <li>+{{ $changes->count() - 4 }} more</li>
                                            @endif
                                        </ul>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-caption text-stone-500">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.panel>

    <div class="mt-6">{{ $logs->links() }}</div>
@endsection
