@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Users')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary btn-sm">New user</a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row">
        <div class="flex-1">
            <label for="search" class="sr-only">Search users</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search by name or email…" class="input">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Search</button>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost">Reset</a>
        </div>
    </form>

    <x-admin.panel>
        @if($users->isEmpty())
            <p class="text-body-sm text-stone-500">No users found.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">Roles</th>
                            <th scope="col" class="hidden md:table-cell">Joined</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-accent-600 text-caption font-medium text-white">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 2)) }}
                                        </span>
                                        <span>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-stone-900 hover:text-accent-600 dark:text-white">{{ $user->name }}</a>
                                            <span class="block text-caption text-stone-500 dark:text-stone-400">{{ $user->email }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($user->roles as $role)
                                            <x-ui.badge variant="outline">{{ $role->name }}</x-ui.badge>
                                        @empty
                                            <span class="text-caption text-stone-400">No role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="hidden px-4 py-3 text-body-sm text-stone-500 md:table-cell">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.panel>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection
