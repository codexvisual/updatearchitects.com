@extends('layouts.admin')

@section('title', 'My Profile')
@section('heading', 'My Profile')

@section('content')
    <div class="grid max-w-5xl gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.panel title="Profile information" subtitle="Your name and email are shown in the activity log.">
                @include('profile.partials.update-profile-information-form')
            </x-admin.panel>

            <x-admin.panel title="Update password" subtitle="Use a long, unique password for this account.">
                @include('profile.partials.update-password-form')
            </x-admin.panel>
        </div>

        <div>
            <x-admin.panel title="Account">
                <dl class="space-y-3 text-body-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-stone-500 dark:text-stone-400">Signed in as</dt>
                        <dd class="font-medium text-stone-900 dark:text-white">{{ auth()->user()->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-stone-500 dark:text-stone-400">Roles</dt>
                        <dd class="text-right font-medium text-stone-900 dark:text-white">
                            {{ auth()->user()->getRoleNames()->join(', ') ?: 'No role assigned' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-stone-500 dark:text-stone-400">Email verified</dt>
                        <dd class="font-medium text-stone-900 dark:text-white">
                            {{ auth()->user()->hasVerifiedEmail() ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </dl>
            </x-admin.panel>

            <div class="mt-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
