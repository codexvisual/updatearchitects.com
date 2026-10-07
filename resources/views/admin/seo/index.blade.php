@extends('layouts.admin')

@section('title', 'SEO')
@section('heading', 'SEO Settings')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.seo.update') }}">
                @csrf
                @method('PUT')

                <x-admin.panel title="Meta tags" subtitle="Used on the default and section landing pages.">
                    <div class="space-y-5">
                        @foreach($fields as $field)
                            @php
                                [$group, $name] = explode('.', $field['key'], 2);
                            @endphp

                            @if(str_contains($name, 'description'))
                                <x-ui.textarea
                                    name="settings[{{ $field['key'] }}]"
                                    label="{{ \Illuminate\Support\Str::headline($name) }}"
                                    rows="2"
                                    :value="old($field['key'], $field['value'])"
                                    helper="Around 155 characters works best."
                                />
                            @else
                                <x-ui.input
                                    name="settings[{{ $field['key'] }}]"
                                    label="{{ \Illuminate\Support\Str::headline($name) }}"
                                    :value="old($field['key'], $field['value'])"
                                />
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary">Save SEO settings</button>
                    </div>
                </x-admin.panel>
            </form>
        </div>

        <div class="space-y-6">
            <x-admin.panel title="Generated files">
                <div class="space-y-3 text-body-sm">
                    <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" class="flex items-center justify-between rounded-lg border border-stone-200 px-4 py-3 text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-800 dark:text-stone-300">
                        <span>sitemap.xml</span>
                        <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('robots') }}" target="_blank" rel="noopener" class="flex items-center justify-between rounded-lg border border-stone-200 px-4 py-3 text-stone-700 hover:border-accent-400 hover:text-accent-700 dark:border-stone-800 dark:text-stone-300">
                        <span>robots.txt</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </x-admin.panel>

            <x-admin.panel title="Notes">
                <ul class="space-y-2 text-body-sm text-stone-600 dark:text-stone-400">
                    <li>Individual projects, services, team members and posts read their own title and description from the record.</li>
                    <li>Leave a field empty to fall back to the site default.</li>
                    <li>Bengali (BN) pages can override any of these through the Settings screen.</li>
                </ul>
            </x-admin.panel>
        </div>
    </div>
@endsection
