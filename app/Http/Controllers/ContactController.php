<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Models\Office;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function create(): View
    {
        $locale = app()->getLocale();

        $seo = [
            'title' => Setting::getValue('seo.contact_title', $locale, 'Contact Us — Update Architects & Engineering'),
            'description' => Setting::getValue(
                'seo.contact_description',
                $locale,
                'Get in touch with Update Architects & Engineering for architecture, engineering, construction consultancy and interior design enquiries.'
            ),
            'canonical' => route('contact'),
        ];

        $offices = Office::visible()
            ->where('locale', $locale)
            ->orderBy('sort_order')
            ->get();

        return view('pages.contact', compact('seo', 'offices'));
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('website');

        $validated['locale'] = $validated['locale'] ?? app()->getLocale();
        $validated['status'] = 'unread';
        $validated['ip_address'] = $request->ip();

        $contactMessage = ContactMessage::create($validated);

        $this->notifyOffice($contactMessage);

        return back()->with('success', 'Thank you for your message. We will get in touch with you soon.');
    }

    /**
     * E-mail the office about the new message.
     *
     * The lead is already persisted, so a mail failure is reported rather than
     * thrown - a misconfigured mailer must never lose a real enquiry.
     */
    private function notifyOffice(ContactMessage $contactMessage): void
    {
        $recipient = Office::enquiryRecipient();

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new NewContactMessage($contactMessage));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
