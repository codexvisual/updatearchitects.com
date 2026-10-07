<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $media = Media::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', "%{$request->string('search')}%"))
            ->when($request->filled('collection'), fn ($query) => $query->where('collection_name', $request->string('collection')))
            ->latest('id')
            ->paginate(24)
            ->withQueryString();

        $collections = Media::query()
            ->select('collection_name')
            ->distinct()
            ->orderBy('collection_name')
            ->pluck('collection_name');

        return view('admin.media.index', compact('media', 'collections'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        foreach ($validated['images'] as $file) {
            $media = $project->addMedia($file)
                ->usingName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                ->toMediaCollection('gallery', 'public');

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

        $count = count($validated['images']);

        return back()->with('success', $count === 1
            ? "Image added to \"{$project->title}\"."
            : "{$count} images added to \"{$project->title}\".");
    }

    public function destroy(Media $media): RedirectResponse
    {
        ProjectImage::query()
            ->where('media_id', $media->id)
            ->get()
            ->each(function (ProjectImage $image) {
                $project = $image->project;

                $image->delete();

                if ($project && $project->featured_image_id === $image->media_id) {
                    $project->update([
                        'featured_image_id' => $project->images()->value('media_id'),
                    ]);
                }
            });

        $media->delete();

        return back()->with('success', 'Media deleted.');
    }
}
