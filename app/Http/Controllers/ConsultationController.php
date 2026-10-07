<?php

namespace App\Http\Controllers;

use App\Mail\NewConsultationLead;
use App\Models\ConsultationLead;
use App\Models\Office;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ConsultationController extends Controller
{
    public function create(): View
    {
        $locale = app()->getLocale();

        $seo = [
            'title' => Setting::getValue('seo.consultation_title', $locale, 'Start Your Project — Update Architects & Engineering'),
            'description' => Setting::getValue(
                'seo.consultation_description',
                $locale,
                'Tell us about your architecture, engineering, construction consultancy or interior design project and get a clear scope from our team.'
            ),
            'canonical' => route('consultation'),
        ];

        return view('pages.consultation', compact('seo'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $throttleKey = 'consultation:'.Str::lower((string) $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $message = 'You have submitted several requests already. Please try again later or call us directly.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 429);
            }

            return back()->withErrors(['email' => $message])->withInput();
        }

        RateLimiter::hit($throttleKey, 3600);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'project_type' => ['nullable', 'string', 'max:100'],
            'project_location' => ['nullable', 'string', 'max:255'],
            'approximate_area' => ['nullable', 'string', 'max:100'],
            'required_services' => ['nullable', 'array'],
            'required_services.*' => ['string', 'max:100'],
            'estimated_budget' => ['nullable', 'string', 'max:100'],
            'expected_start_date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:5000'],
            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:10240'],
            'consent' => ['required', 'accepted'],
            'contact_method' => ['nullable', 'in:phone,email,whatsapp'],
            'language' => ['nullable', 'in:en,bn'],
            'website' => ['nullable', 'size:0'],
        ], [
            'name.required' => 'Your name is required.',
            'phone.required' => 'Phone number is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'files.max' => 'You can upload up to 5 files.',
            'files.*.max' => 'Each file must be 10 MB or smaller.',
            'consent.required' => 'You must agree to the privacy policy.',
            'consent.accepted' => 'You must agree to the privacy policy.',
        ]);

        $filePaths = [];

        foreach ($request->file('files', []) as $file) {
            $filePaths[] = $file->store('consultations/'.date('Y/m'), 'local');
        }

        $utm = array_filter($request->only([
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ]));

        $lead = ConsultationLead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'project_type' => $validated['project_type'] ?? null,
            'project_location' => $validated['project_location'] ?? null,
            'approximate_area' => $validated['approximate_area'] ?? null,
            'required_services' => $validated['required_services'] ?? [],
            'estimated_budget' => $validated['estimated_budget'] ?? null,
            'expected_start_date' => $validated['expected_start_date'] ?? null,
            'message' => $validated['message'] ?? null,
            'files' => $filePaths,
            'consent' => true,
            'contact_method' => $validated['contact_method'] ?? 'phone',
            'language' => $validated['language'] ?? app()->getLocale(),
            'source' => $request->header('referer') ? 'website' : 'direct',
            'utm' => $utm ?: null,
            'status' => 'new',
            'locale' => app()->getLocale(),
            'ip_address' => $request->ip(),
        ]);

        $lead->statusHistory()->create([
            'from_status' => null,
            'to_status' => 'new',
            'note' => 'Lead created from the website consultation form',
        ]);

        $this->notifyOffice($lead);

        $message = 'Thank you. Your consultation request has been submitted and our team will get in touch.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'lead_id' => $lead->id,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * E-mail the office about the new lead.
     *
     * The lead is already persisted, so a mail failure is reported rather than
     * thrown - a misconfigured mailer must never lose a real enquiry.
     */
    private function notifyOffice(ConsultationLead $lead): void
    {
        $recipient = Office::enquiryRecipient();

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new NewConsultationLead($lead));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
