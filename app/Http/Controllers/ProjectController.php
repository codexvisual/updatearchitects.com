<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $locale = app()->getLocale();

        $query = Project::published()
            ->where('locale', $locale)
            ->with(['featuredImage', 'translations' => fn ($q) => $q->where('locale', $locale)]);

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('sort_order')->paginate(12)->withQueryString();

        $categories = ProjectCategory::where('locale', $locale)->orderBy('sort_order')->get();
        $statuses = ['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'];

        return view('pages.projects.index', compact('projects', 'categories', 'statuses'));
    }

    public function show(Project $project): View
    {
        $locale = app()->getLocale();

        // Load translations for current locale
        $project->loadMissing([
            'featuredImage',
            'images.media',
            'videos.media',
            'documents.media',
            'progressItems',
            'services',
            'teamMembers',
            'translations' => fn ($q) => $q->where('locale', $locale),
        ]);

        // Get related projects
        $relatedProjects = Project::published()
            ->where('locale', $locale)
            ->where('id', '!=', $project->id)
            ->where(function ($q) use ($project) {
                $q->where('category', $project->category)
                    ->orWhereHas('services', fn ($sq) => $sq->whereIn('services.id', $project->services->pluck('id')));
            })
            ->with(['featuredImage'])
            ->limit(3)
            ->get();

        // SEO
        $seo = $project->seo ?? [];
        $seo += [
            'title' => $project->title,
            'description' => $project->summary ?? Str::limit($project->description, 160),
            'canonical' => route('projects.show', $project->slug),
            'og_image' => $project->featuredImage?->getUrl(),
        ];

        // Schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'dateCreated' => optional($project->published_at)->toAtomString(),
            'name' => $project->title,
            'description' => $seo['description'],
            'url' => $seo['canonical'],
            'image' => $seo['og_image'],
            'location' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $project->location,
                ],
            ],
        ];

        return view('pages.projects.show', compact('project', 'relatedProjects', 'seo', 'schema'));
    }
}
