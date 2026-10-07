<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProjectCategory::query()
            ->withCount('projects')
            ->orderBy('sort_order')
            ->paginate(25);

        return view('admin.project-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.project-categories.create', [
            'category' => new ProjectCategory(['locale' => 'en', 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = ProjectCategory::create($this->validated($request));

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    public function show(ProjectCategory $category): View
    {
        $category->loadCount('projects');

        return view('admin.project-categories.show', compact('category'));
    }

    public function edit(ProjectCategory $category): View
    {
        return view('admin.project-categories.edit', compact('category'));
    }

    public function update(Request $request, ProjectCategory $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroy(ProjectCategory $category): RedirectResponse
    {
        $name = $category->name;

        $category->delete();

        return back()->with('success', "Category \"{$name}\" removed.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?ProjectCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('project_categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['name']);

        return $validated;
    }
}
