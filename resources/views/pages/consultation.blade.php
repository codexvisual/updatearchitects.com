@php
    use App\Models\Office;
    use App\Models\Setting;

    $locale = app()->getLocale();
    $offices = Office::visible()->where('locale', $locale)->orderBy('sort_order')->get();
@endphp
<x-layout.app :title="$seo['title']" :description="$seo['description']" :canonicalUrl="$seo['canonical'] ?? null">
    {{-- Page header --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-20">
            <p class="text-overline text-accent-600 mb-4">Consultation</p>
            <h1 class="font-display text-display-lg mb-4">{{ Setting::getValue('consultation.heading', $locale, 'Start Your Project') }}</h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl">
                {{ Setting::getValue('consultation.intro', $locale, 'Tell us about the site, the scope and the timeline. Our team reviews every request and comes back with a clear way forward.') }}
            </p>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        <div class="grid gap-12 lg:grid-cols-[1fr_320px]">
            <div>
                <x-forms.consultation-form />
            </div>

            <aside class="space-y-6">
                <div class="rounded-xl border border-stone-200 p-6 dark:border-stone-800">
                    <h2 class="font-display text-heading-lg mb-4">What happens next</h2>
                    <ol class="space-y-4">
                        @php
                            $steps = [
                                'We review your requirements and site details.',
                                'We confirm the disciplines you need and the likely scope.',
                                'We arrange a call or site visit as required.',
                                'You receive a written proposal with fees and timeline.',
                            ];
                        @endphp
                        @foreach($steps as $index => $step)
                            <li class="flex gap-3">
                                <span class="inline-flex items-center justify-center h-6 w-6 shrink-0 rounded-full bg-accent-100 text-accent-700 text-caption font-medium dark:bg-accent-900/30 dark:text-accent-300">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-body-sm text-stone-600 dark:text-stone-400">{{ $step }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @if($offices->isNotEmpty())
                    <div class="rounded-xl border border-stone-200 p-6 dark:border-stone-800">
                        <h2 class="font-display text-heading-lg mb-4">Prefer to talk?</h2>
                        <div class="space-y-4">
                            @foreach($offices as $office)
                                <div>
                                    <h3 class="font-medium text-stone-900 dark:text-white mb-1">{{ $office->name }}</h3>
                                    @if($office->phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office->phone) }}" class="block text-body-sm text-accent-600 hover:text-accent-700">
                                            {{ $office->phone }}
                                        </a>
                                    @endif
                                    @if($office->email)
                                        <a href="mailto:{{ $office->email }}" class="block text-body-sm text-stone-600 hover:text-accent-600 dark:text-stone-400 break-all">
                                            {{ $office->email }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-6 border-t border-stone-200 dark:border-stone-800">
                            <a href="https://wa.me/8801751585650?text=Hello!%20I%20would%20like%20to%20discuss%20a%20project." target="_blank" rel="noopener noreferrer" class="btn-primary w-full bg-[#25D366] hover:bg-[#1da851] border-[#25D366] text-white">
                                Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-layout.app>
