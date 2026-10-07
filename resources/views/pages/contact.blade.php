@php
    use App\Models\Setting;

    $locale = app()->getLocale();
@endphp
<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null">
    {{-- Page header --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-20">
            <p class="text-overline text-accent-600 mb-4">Contact</p>
            <h1 class="font-display text-display-lg mb-4">{{ Setting::getValue('contact.heading', $locale, 'Get in touch') }}</h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl">
                {{ Setting::getValue('contact.intro', $locale, 'Questions about a project, an approval or a quotation? Send us a message and our team will respond.') }}
            </p>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        <div class="grid gap-12 lg:grid-cols-[1fr_380px]">
            {{-- Form --}}
            <div>
                @if(session('success'))
                    <div class="mb-8 rounded-lg border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900 dark:bg-emerald-950/40" role="status">
                        <p class="text-body-sm text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-8 rounded-lg border border-red-200 bg-red-50 p-5 dark:border-red-900 dark:bg-red-950/40" role="alert">
                        <p class="text-body-sm font-medium text-red-800 dark:text-red-300 mb-2">Please fix the following:</p>
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-body-sm text-red-700 dark:text-red-400">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="grid gap-5 sm:grid-cols-2" novalidate>
                    @csrf
                    <input type="text" name="website" value="" class="sr-only" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="sm:col-span-1">
                        <x-ui.input name="name" label="Your Name *" :value="old('name')" required autocomplete="name" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-ui.input name="email" type="email" label="Email Address *" :value="old('email')" required autocomplete="email" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-ui.input name="phone" type="tel" label="Phone Number" :value="old('phone')" autocomplete="tel" />
                    </div>
                    <div class="sm:col-span-1">
                        <x-ui.input name="subject" label="Subject" :value="old('subject')" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-ui.textarea name="message" label="Message *" rows="6" required :value="old('message')" />
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-primary w-full sm:w-auto">Send Message</button>
                    </div>
                </form>
            </div>

            {{-- Offices --}}
            <aside>
                <div class="rounded-xl border border-stone-200 p-6 dark:border-stone-800">
                    <h2 class="font-display text-heading-lg mb-5">Our Offices</h2>

                    <div class="space-y-6">
                        @forelse($offices as $office)
                            <div class="pb-6 border-b border-stone-200 last:border-0 last:pb-0 dark:border-stone-800">
                                <h3 class="font-medium text-heading-md text-stone-900 dark:text-white mb-2">{{ $office->name }}</h3>

                                @if($office->address)
                                    <address class="not-italic text-body-sm text-stone-600 dark:text-stone-400">{!! nl2br(e($office->address)) !!}</address>
                                @endif

                                <div class="mt-2 space-y-1 text-body-sm">
                                    @if($office->phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}" class="flex items-center gap-2 py-2.5 text-stone-600 hover:text-accent-600 dark:text-stone-400">
                                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 0 1 2-2h2l2 5-2 1a12 12 0 0 0 6 6l1-2 5 2v2a2 2 0 0 1-2 2A17 17 0 0 1 3 5Z"/>
                                            </svg>
                                            {{ $office->phone }}
                                        </a>
                                    @endif
                                    @if($office->email)
                                        <a href="mailto:{{ $office->email }}" class="flex items-center gap-2 py-2.5 text-stone-600 hover:text-accent-600 dark:text-stone-400 break-all">
                                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6h18v12H3zM3 7l9 6 9-6"/>
                                            </svg>
                                            {{ $office->email }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-body-sm text-stone-500">Office details will be published here shortly.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 rounded-xl bg-accent-700 p-6 text-white">
                    <h2 class="font-display text-heading-md mb-2">Planning a project?</h2>
                    <p class="text-body-sm text-accent-100 mb-5">The consultation form captures everything we need to prepare a scope.</p>
                    <a href="{{ route('consultation') }}" class="btn-secondary bg-white text-accent-700 hover:bg-accent-50 border-transparent w-full">
                        Start Your Project
                    </a>
                </div>
            </aside>
        </div>
    </div>

    {{-- Find us on the map --}}
    <section class="section section-alt" aria-labelledby="map-heading">
        <div class="container">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <p class="text-overline text-accent-600 mb-4">Find Us</p>
                <h2 id="map-heading" class="font-display text-display-md mb-6">Our Offices on the Map</h2>
                <p class="text-body-lg text-stone-600 dark:text-stone-400">
                    We operate from Kurigram and Rangpur — hover any district to explore, and look for our office markers.
                </p>
            </div>

            <div class="mx-auto max-w-[820px]">
                <x-maps.bangladesh-map />

                <div class="mt-8 flex items-center justify-center gap-2 text-caption text-stone-500 dark:text-stone-400">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-accent-600" aria-hidden="true"></span>
                    Office locations — Kurigram &amp; Rangpur
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
