<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectProgressItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectProgressController extends Controller
{
    public function index(Project $project): View
    {
        $progressItems = $project->progressItems()->orderBy('sort_order')->paginate(25);

        return view('admin.projects.progress.index', compact('project', 'progressItems'));
    }

    public function create(Project $project): View
    {
        $item = new ProjectProgressItem([
            'status' => 'pending',
            'visibility' => true,
            'locale' => 'en',
            'sort_order' => (int) $project->progressItems()->max('sort_order') + 1,
        ]);

        return view('admin.projects.progress.create', compact('project', 'item'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validated($request);

        $project->progressItems()->create($validated + ['locale' => 'en']);

        return redirect()
            ->route('admin.projects.progress.index', $project)
            ->with('success', "Progress stage \"{$validated['title']}\" added.");
    }

    public function show(ProjectProgressItem $progress): RedirectResponse
    {
        return redirect()->route('admin.progress.edit', $progress);
    }

    public function edit(ProjectProgressItem $progress): View
    {
        $progress->load('project');

        return view('admin.projects.progress.edit', compact('progress'));
    }

    public function update(Request $request, ProjectProgressItem $progress): RedirectResponse
    {
        $progress->update($this->validated($request, $progress));

        return redirect()
            ->route('admin.projects.progress.index', $progress->project)
            ->with('success', "Progress stage \"{$progress->title}\" updated.");
    }

    public function destroy(ProjectProgressItem $progress): RedirectResponse
    {
        $project = $progress->project;

        $progress->delete();

        return redirect()
            ->route('admin.projects.progress.index', $project)
            ->with('success', 'Progress stage removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?ProjectProgressItem $progress = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('project_progress_items', 'slug')->ignore($progress?->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'on_hold', 'not_applicable'])],
            'is_completed' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'visibility' => ['nullable', 'boolean'],
            'internal_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['title']);
        $validated['is_completed'] = $request->boolean('is_completed');
        $validated['visibility'] = $request->boolean('visibility');

        return $validated;
    }
}
