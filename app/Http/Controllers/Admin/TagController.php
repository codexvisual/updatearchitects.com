<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->orderBy('name')
            ->paginate(25);

        return view('admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.tags.create', ['tag' => new Tag(['locale' => 'en'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tag = Tag::create($this->validated($request));

        return redirect()
            ->route('admin.tags.index')
            ->with('success', "Tag \"{$tag->name}\" created.");
    }

    public function show(Tag $tag): View
    {
        $tag->loadCount('posts');

        return view('admin.tags.show', compact('tag'));
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $tag->update($this->validated($request, $tag));

        return redirect()
            ->route('admin.tags.index')
            ->with('success', "Tag \"{$tag->name}\" updated.");
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $name = $tag->name;

        $tag->delete();

        return back()->with('success', "Tag \"{$name}\" removed.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Tag $tag = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('tags', 'slug')->ignore($tag?->id)],
            'locale' => ['required', Rule::in(['en', 'bn'])],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['name']);

        return $validated;
    }
}
