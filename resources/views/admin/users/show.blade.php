@extends('layouts.admin')

@section('title', $user->name)
@section('heading', $user->name)

@section('actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Account">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-overline text-stone-400">Name</dt>
                        <dd class="text-body text-stone-900 dark:text-white">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Email</dt>
                        <dd class="break-all text-body text-stone-900 dark:text-white">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Email verified</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="$user->email_verified_at ? 'success' : 'secondary'">
                                {{ $user->email_verified_at ? 'Verified' : 'Not verified' }}
                            </x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Joined</dt>
                        <dd class="text-body text-stone-900 dark:text-white">{{ $user->created_at->format('M j, Y') }}</dd>
                    </div>
                </dl>
            </x-admin.panel>
        </div>

        <div>
            <x-admin.panel title="Roles">
                @forelse($user->roles as $role)
                    <a href="{{ route('admin.roles.show', $role) }}" class="mb-1 block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">{{ $role->name }}</a>
                @empty
                    <p class="text-body-sm text-stone-500">No roles assigned.</p>
                @endforelse
            </x-admin.panel>
        </div>
    </div>
@endsection
