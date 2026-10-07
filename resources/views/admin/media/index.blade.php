@extends('layouts.admin')

@section('title', 'Media Library')
@section('heading', 'Media Library')

@section('content')
    <div class="grid gap-6 lg:grid-cols-4">
        <div class="lg:col-span-3">
            <form method="GET" action="{{ route('admin.media.index') }}" class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_auto_auto] lg:items-center">
                <div>
                    <label for="search" class="sr-only">Search media</label>
                    <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Search by file name…" class="input">
                </div>
                <div>
                    <label for="collection" class="sr-only">Collection</label>
                    <select id="collection" name="collection" class="input" onchange="this.form.submit()">
                        <option value="">All collections</option>
                        @foreach($collections as $collection)
                            <option value="{{ $collection }}" @selected(request('collection') === $collection)>{{ $collection }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 lg:flex-none">Filter</button>
                    <a href="{{ route('admin.media.index') }}" class="btn-ghost">Reset</a>
                </div>
            </form>

            @if($media->isEmpty())
                <x-admin.panel>
                    <p class="text-body-sm text-stone-500">No media uploaded yet. Use the panel on the right to add images to a project.</p>
                </x-admin.panel>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($media as $item)
                        <figure class="overflow-hidden rounded-xl border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
                            <div class="aspect-video bg-stone-100 dark:bg-stone-800">
                                <img src="{{ $item->getAvailableUrl(['thumbnail']) }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy">
                            </div>
                            <figcaption class="p-4">
                                <p class="truncate text-body-sm font-medium text-stone-900 dark:text-white">{{ $item->name }}</p>
                                <p class="mt-0.5 text-caption text-stone-500 dark:text-stone-400">
                                    {{ $item->collection_name }} · {{ number_format($item->size / 1024) }} KB
                                </p>
                                <div class="mt-3 flex items-center gap-2">
                                    <a href="{{ $item->getUrl() }}" target="_blank" rel="noopener" class="btn-ghost px-2 py-1 text-caption">Open</a>
                                    <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('Delete this file?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-caption text-red-600 dark:text-red-400">Delete</button>
                                    </form>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>

                <div class="mt-6">{{ $media->links() }}</div>
            @endif
        </div>

        <div>
            <x-admin.panel title="Upload images" subtitle="Images are attached to a project's gallery collection.">
                <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <x-ui.select
                        name="project_id"
                        label="Project *"
                        :options="\App\Models\Project::orderBy('title')->pluck('title', 'id')->all()"
                        :value="old('project_id')"
                        placeholder="Select a project"
                        required
                    />

                    <x-forms.file-upload
                        name="images[]"
                        label="Images"
                        helper="JPEG, PNG or WebP up to 10 MB each, maximum 20 files."
                        :multiple="true"
                        :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                        :maxSizeMB="10"
                    />

                    <button type="submit" class="btn-primary w-full">Upload</button>
                </form>
            </x-admin.panel>
        </div>
    </div>
@endsection
