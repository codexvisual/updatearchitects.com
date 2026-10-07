<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::query()
            ->withCount('posts')
            ->orderBy('sort_order')
            ->paginate(25);

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.blog-categories.create', [
            'category' => new BlogCategory(['locale' => 'en', 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = BlogCategory::create($this->validated($request));

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    public function show(BlogCategory $category): View
    {
        $category->loadCount('posts');

        return view('admin.blog-categories.show', compact('category'));
    }

    public function edit(BlogCategory $category): View
    {
        return view('admin.blog-categories.edit', compact('category'));
    }

    public function update(Request $request, BlogCategory $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        $name = $category->name;

        $category->delete();

        return back()->with('success', "Category \"{$name}\" removed.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?BlogCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['name']);

        return $validated;
    }
}
