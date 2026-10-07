<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::query()
            ->latest('sort_order')
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $page = new Page([
            'status' => 'draft',
            'locale' => 'en',
            'content_blocks' => [],
        ]);

        return view('admin.pages.create', compact('page'));
    }

    public function store(Request $request): RedirectResponse
    {
        $page = Page::create($this->validated($request));

        return redirect()
            ->route('admin.pages.index')
            ->with('success', "Page \"{$page->title}\" created.");
    }

    public function show(Page $page): View
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validated($request, $page));

        return redirect()
            ->route('admin.pages.index')
            ->with('success', "Page \"{$page->title}\" updated.");
    }

    public function destroy(Page $page): RedirectResponse
    {
        $title = $page->title;

        $page->delete();

        return back()->with('success', "Page \"{$title}\" moved to trash.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Page $page = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('pages', 'slug')->ignore($page?->id)],
            'content' => ['nullable', 'string', 'max:100000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'locale' => ['required', Rule::in(['en', 'bn'])],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
        ]);

        return [
            'title' => $validated['title'],
            'slug' => ($validated['slug'] ?? '') ?: Str::slug($validated['title']),
            'content_blocks' => filled($validated['content'] ?? null)
                ? [['type' => 'html', 'content' => $validated['content']]]
                : [],
            'status' => $validated['status'],
            'locale' => $validated['locale'],
            'published_at' => $validated['status'] === 'published'
                ? ($validated['published_at'] ?? $page?->published_at ?? now())
                : null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'seo' => array_filter([
                'title' => $validated['seo_title'] ?? null,
                'description' => $validated['seo_description'] ?? null,
            ]),
        ];
    }
}
