<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $categories = ServiceCategory::where('locale', $locale)
            ->with(['services' => fn ($q) => $q->visible()->where('locale', $locale)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('pages.services.index', compact('categories'));
    }

    public function show(Service $service): View
    {
        $locale = app()->getLocale();

        $service->loadMissing([
            'featuredImage',
            'category',
            'children' => fn ($q) => $q->visible()->where('locale', $locale)->orderBy('sort_order'),
            'projects' => fn ($q) => $q->published()->where('locale', $locale)->with(['featuredImage'])->orderBy('sort_order')->limit(6),
            'teamMembers' => fn ($q) => $q->visible()->where('locale', $locale)->with(['photo'])->orderBy('sort_order')->limit(6),
            'translations' => fn ($q) => $q->where('locale', $locale),
        ]);

        $seo = $service->seo ?? [];
        $seo += [
            'title' => $service->name,
            'description' => $service->short_description ?? Str::limit($service->full_description, 160),
            'canonical' => route('services.show', $service->slug),
            'og_image' => $service->featuredImage?->getUrl(),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->name,
            'description' => $seo['description'],
            'url' => $seo['canonical'],
            'provider' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
            ],
        ];

        return view('pages.services.show', compact('service', 'seo', 'schema'));
    }

    public function architecture(): View
    {
        return $this->showByCategory('architecture');
    }

    public function structural(): View
    {
        return $this->showByCategory('structural');
    }

    public function geotechnical(): View
    {
        return $this->showByCategory('geotechnical');
    }

    public function construction(): View
    {
        return $this->showByCategory('construction');
    }

    public function interior(): View
    {
        return $this->showByCategory('interior');
    }

    public function mep(): View
    {
        return $this->showByCategory('mep');
    }

    public function approval(): View
    {
        return $this->showByCategory('approval');
    }

    public function documentation(): View
    {
        return $this->showByCategory('documentation');
    }

    private function showByCategory(string $categorySlug): View
    {
        $locale = app()->getLocale();

        $category = ServiceCategory::where('locale', $locale)
            ->where('slug', $categorySlug)
            ->with(['services' => fn ($q) => $q->visible()->where('locale', $locale)->with(['featuredImage'])->orderBy('sort_order')])
            ->firstOrFail();

        return view('pages.services.category', compact('category'));
    }
}
