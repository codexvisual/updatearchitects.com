@extends('layouts.admin')

@section('title', $role->name)
@section('heading', $role->name)

@section('actions')
    <a href="{{ route('admin.roles.edit', $role) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Permissions" :subtitle="$role->permissions->count().' granted'">
                @if($role->permissions->isEmpty())
                    <p class="text-body-sm text-stone-500">No permissions assigned to this role.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($role->permissions as $permission)
                            <x-ui.badge variant="outline">{{ $permission->name }}</x-ui.badge>
                        @endforeach
                    </div>
                @endif
            </x-admin.panel>
        </div>

        <div>
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Users with this role</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $role->users()->count() }}</dd>
                    </div>
                </dl>
            </x-admin.panel>
        </div>
    </div>
@endsection
