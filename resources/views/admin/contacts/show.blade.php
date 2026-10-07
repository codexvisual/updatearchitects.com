@extends('layouts.admin')

@section('title', $message->name)
@section('heading', 'Message from '.$message->name)

@section('actions')
    <a href="mailto:{{ $message->email }}" class="btn-primary btn-sm">Reply by email</a>
    <a href="{{ route('admin.contacts.edit', $message) }}" class="btn-ghost btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel :title="$message->subject ?: 'Message'">
                <div class="whitespace-pre-line text-body text-stone-700 dark:text-stone-300">{{ $message->message }}</div>
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Sender">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Name</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $message->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Email</dt>
                        <dd class="break-all text-body-sm"><a href="mailto:{{ $message->email }}" class="text-accent-600">{{ $message->email }}</a></dd>
                    </div>
                    @if($message->phone)
                        <div>
                            <dt class="text-overline text-stone-400">Phone</dt>
                            <dd class="text-body-sm"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $message->phone) }}" class="text-accent-600">{{ $message->phone }}</a></dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-overline text-stone-400">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="match ($message->status) {
                                'unread' => 'accent',
                                'replied' => 'success',
                                'archived' => 'secondary',
                                default => 'outline',
                            }">
                                {{ ucfirst($message->status) }}
                            </x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Received</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $message->created_at->format('M j, Y H:i') }}</dd>
                    </div>
                    @if($message->ip_address)
                        <div>
                            <dt class="text-overline text-stone-400">IP address</dt>
                            <dd class="text-body-sm text-stone-900 dark:text-white">{{ $message->ip_address }}</dd>
                        </div>
                    @endif
                </dl>
            </x-admin.panel>

            <x-admin.panel>
                <form method="POST" action="{{ route('admin.contacts.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-outline w-full border-red-300 text-red-600 hover:bg-red-600 hover:text-white dark:border-red-900 dark:text-red-400">
                        Delete message
                    </button>
                </form>
            </x-admin.panel>
        </div>
    </div>
@endsection
