<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ServiceCategory::query()
            ->withCount('services')
            ->orderBy('sort_order')
            ->paginate(25);

        return view('admin.service-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.service-categories.create', [
            'category' => new ServiceCategory(['locale' => 'en', 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = ServiceCategory::create($this->validated($request));

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    public function show(ServiceCategory $category): View
    {
        $category->load('services');

        return view('admin.service-categories.show', compact('category'));
    }

    public function edit(ServiceCategory $category): View
    {
        return view('admin.service-categories.edit', compact('category'));
    }

    public function update(Request $request, ServiceCategory $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroy(ServiceCategory $category): RedirectResponse
    {
        $name = $category->name;

        $category->delete();

        return back()->with('success', "Category \"{$name}\" removed.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?ServiceCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('service_categories', 'slug')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['name']);

        return $validated;
    }
}
