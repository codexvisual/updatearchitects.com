@php
    $projectTypes = [
        'residential' => 'Residential',
        'commercial' => 'Commercial',
        'industrial' => 'Industrial',
        'institutional' => 'Institutional',
        'interior' => 'Interior Design',
        'renovation' => 'Renovation',
        'other' => 'Other',
    ];
    $serviceOptions = [
        'architecture' => 'Architecture',
        'structural' => 'Structural Engineering',
        'geotechnical' => 'Geotechnical Engineering',
        'construction' => 'Construction Consultancy',
        'interior' => 'Interior Design',
        'mep' => 'MEP Design',
        'approval' => 'Approvals (RAJUK, City Corp, etc.)',
        'documentation' => 'Bank Loan Documentation',
        'other' => 'Other',
    ];
    $budgetRanges = [
        'under-10lac' => 'Under 10 Lakh BDT',
        '10-25lac' => '10 - 25 Lakh BDT',
        '25-50lac' => '25 - 50 Lakh BDT',
        '50lac-1cr' => '50 Lakh - 1 Crore BDT',
        '1-3cr' => '1 - 3 Crore BDT',
        '3-5cr' => '3 - 5 Crore BDT',
        '5cr+' => '5 Crore+ BDT',
        'not-sure' => 'Not Sure Yet',
    ];
@endphp

<form
    action="{{ $action ?? route('consultation.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6"
    novalidate
>
    @csrf

    {{-- Honeypot field --}}
    <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="sr-only" aria-hidden="true">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/40" role="status">
            <p class="text-body-sm text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error summary --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-5 dark:border-red-900 dark:bg-red-950/40" role="alert" aria-labelledby="consultation-errors">
            <p id="consultation-errors" class="text-body-sm font-medium text-red-800 dark:text-red-300 mb-2">Please fix the following:</p>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-body-sm text-red-700 dark:text-red-400">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Section 1: Personal Information --}}
    <fieldset class="space-y-4">
        <legend class="font-display text-heading-md text-stone-900 dark:text-white mb-1">Personal Information</legend>

        <div class="grid gap-4 md:grid-cols-2">
            <x-ui.input
                name="name"
                label="Full Name"
                placeholder="Your full name"
                :value="old('name')"
                required
                autocomplete="name"
            />

            <x-ui.input
                name="phone"
                type="tel"
                label="Phone Number"
                placeholder="+880 1XXXXXXXXX"
                :value="old('phone')"
                required
                autocomplete="tel"
            />
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <x-ui.input
                name="email"
                type="email"
                label="Email Address"
                placeholder="your@email.com"
                :value="old('email')"
                required
                autocomplete="email"
            />

            <x-ui.select
                name="contact_method"
                label="Preferred Contact Method"
                :options="['phone' => 'Phone Call', 'email' => 'Email', 'whatsapp' => 'WhatsApp']"
                :value="old('contact_method', 'phone')"
            />
        </div>

        <x-ui.select
            name="language"
            label="Preferred Language"
            :options="['en' => 'English', 'bn' => 'বাংলা (Bengali)']"
            :value="old('language', app()->getLocale())"
        />
    </fieldset>

    {{-- Section 2: Project Details --}}
    <fieldset class="space-y-4 pt-6 border-t border-stone-200 dark:border-stone-800">
        <legend class="font-display text-heading-md text-stone-900 dark:text-white mb-1">Project Details</legend>

        <x-ui.select
            name="project_type"
            label="Project Type"
            :options="$projectTypes"
            :value="old('project_type')"
        />

        <div class="grid gap-4 md:grid-cols-2">
            <x-ui.input
                name="project_location"
                label="Project Location"
                placeholder="City, Area, Address"
                :value="old('project_location')"
            />

            <x-ui.input
                name="approximate_area"
                label="Approximate Area"
                placeholder="e.g., 1,550 sq.ft"
                :value="old('approximate_area')"
            />
        </div>

        <div>
            <span class="label">Required Services</span>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($serviceOptions as $key => $label)
                    <label class="flex items-center gap-2 p-3 border border-stone-200 rounded-lg cursor-pointer hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800 transition-colors">
                        <input
                            type="checkbox"
                            name="required_services[]"
                            value="{{ $key }}"
                            class="w-4 h-4 text-accent-600 border-stone-300 rounded focus:ring-accent-500"
                            @checked(in_array($key, old('required_services', []), true))
                        >
                        <span class="text-body-sm text-stone-700 dark:text-stone-300">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <x-ui.select
                name="estimated_budget"
                label="Estimated Budget"
                :options="$budgetRanges"
                :value="old('estimated_budget')"
            />

            <x-ui.input
                name="expected_start_date"
                type="date"
                label="Expected Start Date"
                :value="old('expected_start_date')"
                min="{{ now()->format('Y-m-d') }}"
            />
        </div>
    </fieldset>

    {{-- Section 3: Additional Information --}}
    <fieldset class="space-y-4 pt-6 border-t border-stone-200 dark:border-stone-800">
        <legend class="font-display text-heading-md text-stone-900 dark:text-white mb-1">Additional Information</legend>

        <x-ui.textarea
            name="message"
            label="Project Description / Message"
            placeholder="Describe your requirements, goals, timeline, and any specific concerns…"
            rows="5"
            :value="old('message')"
        />

        <x-forms.file-upload
            name="files"
            label="Supporting Documents (Optional)"
            helper="Site plans, sketches, reference images or other relevant documents. PDF, JPG, PNG, DOC, DOCX — up to 10 MB each, maximum 5 files."
            :multiple="true"
            :acceptedTypes="['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx']"
            :maxSizeMB="10"
            :error="$errors->first('files')"
        />
    </fieldset>

    {{-- Section 4: Consent --}}
    <fieldset class="pt-6 border-t border-stone-200 dark:border-stone-800">
        <div class="flex items-start gap-3">
            <input
                type="checkbox"
                name="consent"
                id="consent"
                value="1"
                class="w-4 h-4 mt-1 text-accent-600 border-stone-300 rounded focus:ring-accent-500"
                required
                @checked(old('consent'))
            >
            <label for="consent" class="text-body-sm text-stone-700 dark:text-stone-300">
                I consent to Update Architects &amp; Engineering collecting and processing my personal data for the purpose of responding to my consultation request. I have read and agree to the <a href="{{ route('privacy') }}" class="underline hover:text-accent-600">Privacy Policy</a>.
                <span class="text-accent-600" aria-hidden="true">*</span>
            </label>
        </div>
        @error('consent')
            <p class="error-text mt-1">{{ $message }}</p>
        @enderror
    </fieldset>

    {{-- Submit --}}
    <div class="pt-4 flex flex-col gap-3 sm:flex-row">
        <x-ui.button type="submit" variant="primary">Submit Consultation Request</x-ui.button>
        <x-ui.button type="reset" variant="ghost">Clear Form</x-ui.button>
    </div>
</form>

