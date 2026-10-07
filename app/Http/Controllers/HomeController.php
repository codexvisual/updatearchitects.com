<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\HeroSlide;
use App\Models\Office;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        // Featured project
        $featuredProject = Project::published()
            ->where('locale', $locale)
            ->where('featured', true)
            ->with(['featuredImage', 'translations' => fn ($q) => $q->where('locale', $locale)])
            ->first();

        // Featured projects for portfolio section
        $projects = Project::published()
            ->where('locale', $locale)
            ->with(['featuredImage', 'translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        // Core services
        $services = Service::visible()
            ->where('locale', $locale)
            ->whereNull('parent_id')
            ->with(['featuredImage', 'category', 'translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();

        // Team members
        $team = TeamMember::visible()
            ->where('locale', $locale)
            ->with(['photo', 'office', 'translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        // Latest blog posts
        $latestPosts = BlogPost::published()
            ->where('locale', $locale)
            ->with(['category', 'featuredImage', 'author', 'translations' => fn ($q) => $q->where('locale', $locale)])
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Offices
        $offices = Office::visible()
            ->where('locale', $locale)
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();

        // CMS-managed hero slides — falls back to the default hero + services
        // carousel when no active slides exist for the current locale.
        $heroSlidesCms = HeroSlide::visible()
            ->where('locale', $locale)
            ->with(['featuredImage', 'mobileImage'])
            ->orderBy('sort_order')
            ->get();

        // The hero falls back to one slide per core service when no CMS slides
        // exist. Those are the same rows the services section just loaded, so
        // reuse them instead of querying the services table a second time.
        $heroServices = $services->take(3);

        // SEO
        $seo = [
            'title' => Setting::getValue('seo.home_title', $locale, config('app.name')),
            'description' => Setting::getValue('seo.home_description', $locale, 'Integrated architecture, engineering, construction consultancy and interior design solutions.'),
            'canonical' => url('/'),
            'og_image' => Setting::getValue('seo.og_image', $locale),
        ];

        // Schema — only fields we can support with real CMS data.
        $office = $offices->first();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => config('app.name'),
            'description' => $seo['description'],
            'url' => url('/'),
        ];

        if ($office) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => trim(preg_replace('/\s+/', ' ', (string) $office->address)),
                'addressCountry' => 'BD',
            ];

            if ($office->phone) {
                $schema['telephone'] = $office->phone;
            }

            if ($office->email) {
                $schema['email'] = $office->email;
            }
        }

        return view('pages.home', compact(
            'featuredProject',
            'projects',
            'services',
            'team',
            'latestPosts',
            'offices',
            'heroServices',
            'heroSlidesCms',
            'seo',
            'schema'
        ));
    }

    public function search(Request $request): View
    {
        $query = $request->get('q', '');
        $locale = app()->getLocale();

        $results = collect();

        if ($query) {
            // Search projects
            $projects = Project::published()
                ->where('locale', $locale)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('location', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->with(['featuredImage'])
                ->limit(10)
                ->get()
                ->map(fn ($p) => [
                    'type' => 'project',
                    'title' => $p->title,
                    'url' => route('projects.show', $p->slug),
                    'excerpt' => $p->summary ?? Str::limit($p->description, 150),
                    'image' => $p->featuredImage?->getUrl(),
                ]);

            // Search services
            $services = Service::visible()
                ->where('locale', $locale)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('short_description', 'like', "%{$query}%");
                })
                ->with(['featuredImage'])
                ->limit(10)
                ->get()
                ->map(fn ($s) => [
                    'type' => 'service',
                    'title' => $s->name,
                    'url' => route('services.show', $s->slug),
                    'excerpt' => $s->short_description,
                    'image' => $s->featuredImage?->getUrl(),
                ]);

            // Search blog posts
            $posts = BlogPost::published()
                ->where('locale', $locale)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                })
                ->with(['category', 'featuredImage'])
                ->limit(10)
                ->get()
                ->map(fn ($p) => [
                    'type' => 'post',
                    'title' => $p->title,
                    'url' => route('blog.show', $p->slug),
                    'excerpt' => $p->excerpt,
                    'image' => $p->featuredImage?->getUrl(),
                ]);

            // Search team
            $team = TeamMember::visible()
                ->where('locale', $locale)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('designation', 'like', "%{$query}%")
                        ->orWhere('expertise', 'like', "%{$query}%");
                })
                ->with(['photo'])
                ->limit(10)
                ->get()
                ->map(fn ($t) => [
                    'type' => 'team',
                    'title' => $t->name,
                    'url' => route('team.show', $t->slug),
                    'excerpt' => $t->designation,
                    'image' => $t->photo?->getUrl(),
                ]);

            $results = $projects->concat($services)->concat($posts)->concat($team);
        }

        return view('pages.search', compact('query', 'results'));
    }
}
