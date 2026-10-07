@extends('layouts.admin')

@section('title', $category->name)
@section('heading', $category->name)

@section('actions')
    <a href="{{ route('admin.service-categories.edit', $category) }}" class="btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.panel title="Services in this category" :subtitle="$category->services->count().' services'">
                @if($category->description)
                    <p class="mb-5 text-body-sm text-stone-600 dark:text-stone-400">{{ $category->description }}</p>
                @endif

                @forelse($category->services as $service)
                    <a href="{{ route('admin.services.edit', $service) }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400">
                        {{ $service->name }}
                    </a>
                @empty
                    <p class="text-body-sm text-stone-500">No services in this category yet.</p>
                @endforelse
            </x-admin.panel>
        </div>

        <div>
            <x-admin.panel title="Details">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-overline text-stone-400">Slug</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $category->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-overline text-stone-400">Sort order</dt>
                        <dd class="text-body-sm text-stone-900 dark:text-white">{{ $category->sort_order }}</dd>
                    </div>
                </dl>
            </x-admin.panel>
        </div>
    </div>
@endsection
