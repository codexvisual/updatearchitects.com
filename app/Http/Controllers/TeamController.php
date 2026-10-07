<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\TeamMember;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $team = TeamMember::visible()
            ->where('locale', $locale)
            ->with(['photo', 'office', 'translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->paginate(12);

        $offices = Office::visible()
            ->where('locale', $locale)
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();

        return view('pages.team.index', compact('team', 'offices'));
    }

    public function show(TeamMember $member): View
    {
        $locale = app()->getLocale();

        $member->loadMissing([
            'photo',
            'office',
            'projects' => fn ($q) => $q->published()->where('locale', $locale)->with(['featuredImage'])->orderBy('sort_order'),
            'services' => fn ($q) => $q->visible()->where('locale', $locale)->orderBy('sort_order'),
            'translations' => fn ($q) => $q->where('locale', $locale),
        ]);

        $summary = collect([$member->designation, $member->qualification, $member->expertise])
            ->filter()
            ->join(' · ');

        $seo = $member->seo ?? [];
        $seo += [
            'title' => $member->name,
            'description' => Str::limit($member->biography ?? $summary, 160),
            'canonical' => route('team.show', $member->slug),
            'og_image' => $member->photo?->getAvailableUrl(['large']),
        ];

        $schema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $member->name,
            'jobTitle' => $member->designation,
            'description' => $member->biography,
            'url' => $seo['canonical'],
            'image' => $seo['og_image'],
            'email' => $member->email,
            'worksFor' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
            ],
        ], fn ($value) => ! is_null($value) && $value !== '');

        return view('pages.team.show', compact('member', 'seo', 'schema'));
    }
}
