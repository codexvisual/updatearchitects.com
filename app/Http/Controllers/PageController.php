<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        abort_unless($page->status === 'published' && $page->published_at?->isPast(), 404);

        $locale = app()->getLocale();

        $page->loadMissing([
            'translations' => fn ($query) => $query->where('locale', $locale),
        ]);

        $seo = $page->seo ?? [];
        $seo += [
            'title' => $page->title,
            'description' => Setting::getValue('seo.default_description', $locale),
            'canonical' => route('page.show', $page->slug),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->title,
            'description' => $seo['description'],
            'url' => $seo['canonical'],
            'inLanguage' => $locale,
        ];

        return view('pages.page.show', compact('page', 'seo', 'schema'));
    }

    public function internationalSop(): View
    {
        $locale = app()->getLocale();

        $content = Setting::getValue('legal.international_sop_content', $locale);

        $seo = [
            'title' => Setting::getValue('seo.international_sop_title', $locale, 'International SOP — Update Architects & Engineering'),
            'description' => Setting::getValue(
                'seo.international_sop_description',
                $locale,
                'The standard operating procedures maintained by Update Architects & Engineering across design, engineering and site supervision.'
            ),
            'canonical' => route('international-sop'),
        ];

        return view('pages.legal.international-sop', compact('content', 'seo'));
    }

    public function privacy(): View
    {
        $locale = app()->getLocale();

        $content = Setting::getValue('legal.privacy_content', $locale);

        $seo = [
            'title' => Setting::getValue('seo.privacy_title', $locale, 'Privacy Policy — Update Architects & Engineering'),
            'description' => Setting::getValue(
                'seo.privacy_description',
                $locale,
                'How Update Architects & Engineering collects, uses and protects the personal information you share with us.'
            ),
            'canonical' => route('privacy'),
        ];

        return view('pages.legal.privacy', compact('content', 'seo'));
    }

    public function terms(): View
    {
        $locale = app()->getLocale();

        $content = Setting::getValue('legal.terms_content', $locale);

        $seo = [
            'title' => Setting::getValue('seo.terms_title', $locale, 'Terms & Conditions — Update Architects & Engineering'),
            'description' => Setting::getValue(
                'seo.terms_description',
                $locale,
                'The terms that govern your use of the Update Architects & Engineering website and our services.'
            ),
            'canonical' => route('terms'),
        ];

        return view('pages.legal.terms', compact('content', 'seo'));
    }
}
