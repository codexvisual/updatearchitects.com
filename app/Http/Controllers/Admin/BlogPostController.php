<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = BlogPost::query()
            ->with(['category', 'author'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->string('search')}%")
                    ->orWhere('excerpt', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        $categories = BlogCategory::orderBy('sort_order')->get();

        return view('admin.blog.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $post = new BlogPost([
            'status' => 'draft',
            'locale' => 'en',
            'author_id' => auth()->id(),
        ]);

        return view('admin.blog.create', [
            'post' => $post,
            ...$this->formOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $post = BlogPost::create($this->payload($request));

        $post->tagRelations()->sync($request->input('tags', []));

        $this->syncImage($post, $request);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', "\"{$post->title}\" created.");
    }

    public function show(BlogPost $post): View
    {
        $post->loadMissing(['category', 'author', 'tagRelations', 'featuredImage']);

        return view('admin.blog.show', compact('post'));
    }

    public function edit(BlogPost $post): View
    {
        $post->loadMissing('tagRelations');

        return view('admin.blog.edit', [
            'post' => $post,
            ...$this->formOptions(),
        ]);
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $post->update($this->payload($request, $post));

        $post->tagRelations()->sync($request->input('tags', []));

        $this->syncImage($post, $request);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', "\"{$post->title}\" updated.");
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $title = $post->title;

        $post->delete();

        return back()->with('success', "\"{$title}\" moved to trash.");
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(Request $request, ?BlogPost $post = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string', 'max:100000'],
            'category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'status' => ['required', Rule::in(['draft', 'published', 'scheduled'])],
            'locale' => ['required', Rule::in(['en', 'bn'])],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $data = collect($validated)->except(['tags', 'seo_title', 'seo_description'])->all();

        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['title']);
        $data['author_id'] = $request->user()->id;
        $data['seo'] = array_filter([
            'title' => $validated['seo_title'] ?? null,
            'description' => $validated['seo_description'] ?? null,
        ]);

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'categories' => BlogCategory::orderBy('sort_order')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ];
    }

    private function syncImage(BlogPost $post, Request $request): void
    {
        if ($request->hasFile('image')) {
            $post
                ->addMedia($request->file('image'))
                ->preservingOriginal()
                ->toMediaCollection('featured', 'public');

            $media = $post->refresh()->getMedia('featured')->last();

            if ($media) {
                $post->update(['featured_image_id' => $media->id]);
            }
        }
    }
}
