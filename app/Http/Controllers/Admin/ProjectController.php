<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('featuredImage')
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->string('search')}%")
                    ->orWhere('location', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new Project([
            'status' => 'upcoming',
            'locale' => 'en',
            'sort_order' => 0,
        ]);

        return view('admin.projects.create', [
            'project' => $project,
            ...$this->formOptions(),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $project = Project::create($this->payload($request));

        $this->syncRelations($project, $request);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Project \"{$project->title}\" created.");
    }

    public function show(Project $project): View
    {
        $project->loadMissing(['featuredImage', 'services', 'teamMembers', 'progressItems', 'images.media']);

        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $project->loadMissing(['services', 'teamMembers', 'images.media']);

        return view('admin.projects.edit', [
            'project' => $project,
            ...$this->formOptions($project),
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($this->payload($request));

        $this->syncRelations($project, $request);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Project \"{$project->title}\" updated.");
    }

    public function destroy(Project $project): RedirectResponse
    {
        $title = $project->title;

        $project->delete();

        return back()->with('success', "Project \"{$title}\" moved to trash.");
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ProjectRequest $request): array
    {
        $data = $request->safe()->except(['services', 'team_members', 'gallery', 'seo_title', 'seo_description', 'publish_now']);

        if (! $request->filled('featured_image_id')) {
            unset($data['featured_image_id']);
        }

        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['title']);
        $data['seo'] = array_filter([
            'title' => $request->input('seo_title'),
            'description' => $request->input('seo_description'),
        ]);

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(?Project $project = null): array
    {
        return [
            'categories' => ProjectCategory::orderBy('sort_order')->get(),
            'allServices' => Service::orderBy('name')->get(['id', 'name']),
            'allTeamMembers' => TeamMember::orderBy('name')->get(['id', 'name']),
            'galleryMedia' => $project
                ? $project->images()->with('media')->get()
                : collect(),
        ];
    }

    private function syncRelations(Project $project, ProjectRequest $request): void
    {
        $project->services()->sync($request->input('services', []));
        $project->teamMembers()->sync($request->input('team_members', []));

        foreach ($request->file('gallery', []) as $file) {
            $media = $project->addMedia($file)->toMediaCollection('gallery', 'public');

            $project->images()->create([
                'media_id' => $media->id,
                'alt' => $project->title,
                'sort_order' => $project->images()->count(),
            ]);
        }

        if (! $project->featured_image_id) {
            $project->update([
                'featured_image_id' => $project->images()->value('media_id'),
            ]);
        }
    }

    public function destroyImage(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_unless($image->project_id === $project->id, 404);

        $wasFeatured = $project->featured_image_id === $image->media_id;

        $image->delete();

        if ($wasFeatured) {
            $project->update([
                'featured_image_id' => $project->images()->value('media_id'),
            ]);
        }

        Media::find($image->media_id)?->delete();

        return back()->with('success', 'Image removed from the gallery.');
    }
}
