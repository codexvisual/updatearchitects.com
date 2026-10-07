<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $team = TeamMember::visible()
            ->where('locale', $locale)
            ->with(['photo', 'office'])
            ->orderBy('sort_order')
            ->get();

        $offices = Office::visible()
            ->where('locale', $locale)
            ->orderBy('sort_order')
            ->get();

        $services = Service::visible()
            ->where('locale', $locale)
            ->whereNull('parent_id')
            ->with('category')
            ->orderBy('sort_order')
            ->get();

        $seo = [
            'title' => Setting::getValue('seo.about_title', $locale, 'About Us — Update Architects & Engineering'),
            'description' => Setting::getValue(
                'seo.about_description',
                $locale,
                'Update Architects & Engineering is a multidisciplinary architecture, engineering and construction consultancy delivering design and site supervision from Kurigram and Rangpur.'
            ),
            'canonical' => route('about'),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'AboutPage',
            'name' => $seo['title'],
            'description' => $seo['description'],
            'url' => $seo['canonical'],
        ];

        return view('pages.about', compact('team', 'offices', 'services', 'seo', 'schema'));
    }
}
