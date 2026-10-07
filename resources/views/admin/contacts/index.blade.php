@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('heading', 'Contact Messages')

@section('content')
    <form method="GET" action="{{ route('admin.contacts.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
        <div>
            <label for="search" class="sr-only">Search messages</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search name, email or message…" class="input">
        </div>
        <div>
            <label for="status" class="sr-only">Status</label>
            <select id="status" name="status" class="input" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach(['unread' => 'Unread', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
            <a href="{{ route('admin.contacts.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">From</th>
                        <th scope="col" class="hidden md:table-cell">Subject</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="hidden lg:table-cell">Received</th>
                        <th scope="col" class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr @class(['font-medium' => $message->status === 'unread'])>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.contacts.show', $message) }}" class="text-stone-900 hover:text-accent-600 dark:text-white">
                                    {{ $message->name }}
                                </a>
                                <p class="text-caption text-stone-500 dark:text-stone-400">{{ $message->email }}</p>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-600 md:table-cell dark:text-stone-400">
                                {{ $message->subject ?: \Illuminate\Support\Str::limit($message->message, 60) }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="match ($message->status) {
                                    'unread' => 'accent',
                                    'replied' => 'success',
                                    'archived' => 'secondary',
                                    default => 'outline',
                                }">
                                    {{ ucfirst($message->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="hidden px-4 py-3 text-body-sm text-stone-500 lg:table-cell">
                                {{ $message->created_at->format('M j, Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.contacts.show', $message) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                    <a href="{{ route('admin.contacts.edit', $message) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-stone-500">
                                No contact messages yet. Messages sent through the contact form appear here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $messages->links() }}</div>
@endsection
