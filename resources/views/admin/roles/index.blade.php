@extends('layouts.admin')

@section('title', 'Roles')
@section('heading', 'Roles &amp; Permissions')

@section('actions')
    <a href="{{ route('admin.roles.create') }}" class="btn-primary btn-sm">New role</a>
@endsection

@section('content')
    <x-admin.panel>
        @if($roles->isEmpty())
            <p class="text-body-sm text-stone-500">No roles yet.</p>
        @else
            <div class="table-container -mx-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Role</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Users</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td class="px-4 py-3 font-medium text-stone-900 dark:text-white">{{ $role->name }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-600 dark:text-stone-400">{{ $role->permissions_count }}</td>
                                <td class="px-4 py-3 text-body-sm text-stone-600 dark:text-stone-400">{{ $role->users_count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn-ghost px-2 py-1 text-caption">View</a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn-ghost px-2 py-1 text-caption">Edit</a>
                                        @if($role->name !== 'super-admin')
                                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.panel>

    <div class="mt-6">{{ $roles->links() }}</div>
@endsection
