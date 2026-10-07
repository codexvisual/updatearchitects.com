@php
    use App\Models\Setting;

    $locale = app()->getLocale();
@endphp
<x-layout.app title="Our Team" description="Meet the architects, engineers and consultants at Update Architects & Engineering." :canonicalUrl="route('team')">
    {{-- Page header --}}
    <section class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-900/40">
        <div class="container py-16 md:py-20">
            <p class="text-overline text-accent-600 mb-4">Our Team</p>
            <h1 class="font-display text-display-lg mb-4">Meet the Experts</h1>
            <p class="text-body-lg text-stone-600 dark:text-stone-400 max-w-2xl">
                {{ Setting::getValue('team.heading_text', $locale, 'A multidisciplinary team of architects, civil, geotechnical and electrical engineers working together on every commission.') }}
            </p>
        </div>
    </section>

    <div class="container py-12 md:py-16">
        @if($offices->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-12">
                @foreach($offices as $office)
                    <span class="inline-flex items-center gap-2 rounded-full border border-stone-200 bg-white px-4 py-2 text-body-sm text-stone-700 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-200">
                        <svg class="h-4 w-4 text-accent-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z" stroke-linejoin="round"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                        {{ $office->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($team as $member)
                <x-ui.card variant="premium" href="{{ route('team.show', $member->slug) }}" class="group p-8 h-full flex flex-col items-center text-center animate-fade-in-up" style="animation-delay: {{ ($loop->index % 3) * 100 }}ms">
                    <div class="relative mb-5">
                        <x-ui.avatar :name="$member->name" :photo="$member->photo" size="lg" class="shadow-elevated" />
                    </div>

                    <h2 class="font-display text-heading-md mb-1">{{ $member->name }}</h2>

                    @if($member->designation)
                        <p class="text-body-sm font-medium text-accent-600 mb-3">{{ $member->designation }}</p>
                    @endif

                    <dl class="space-y-1 text-body-sm text-stone-600 dark:text-stone-400 mt-auto">
                        @if($member->qualification)
                            <div>{{ $member->qualification }}</div>
                        @endif
                        @if($member->registration)
                            <div class="text-stone-500 dark:text-stone-400">{{ $member->registration }}</div>
                        @endif
                    </dl>
                </x-ui.card>
            @empty
                <div class="col-span-full">
                    <x-ui.placeholder ratio="wide" icon="avatar" label="Team profiles will be published here shortly." class="border border-dashed border-stone-300 dark:border-stone-700" />
                </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $team->links() }}</div>
    </div>
</x-layout.app>
