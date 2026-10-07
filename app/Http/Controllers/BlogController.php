<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $locale = app()->getLocale();

        $query = BlogPost::published()
            ->where('locale', $locale)
            ->with(['category', 'featuredImage', 'author', 'translations' => fn ($q) => $q->where('locale', $locale)]);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tagRelations', fn ($q) => $q->where('slug', $request->tag));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(10)->withQueryString();

        $categories = BlogCategory::where('locale', $locale)->withCount(['posts' => fn ($q) => $q->published()])->orderBy('sort_order')->get();
        $tags = Tag::where('locale', $locale)->whereHas('posts', fn ($q) => $q->published())->get();

        return view('pages.blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(BlogPost $post): View
    {
        $locale = app()->getLocale();

        $post->loadMissing([
            'category',
            'featuredImage',
            'author',
            'tagRelations',
            'translations' => fn ($q) => $q->where('locale', $locale),
        ]);

        // Related posts
        $relatedPosts = BlogPost::published()
            ->where('locale', $locale)
            ->where('id', '!=', $post->id)
            ->whereHas('category', fn ($q) => $q->where('id', $post->category_id))
            ->with(['category', 'featuredImage'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        $seo = $post->seo ?? [];
        $seo += [
            'title' => $post->title,
            'description' => $post->excerpt ?? Str::limit(strip_tags($post->content), 160),
            'canonical' => route('blog.show', $post->slug),
            'og_image' => $post->featuredImage?->getUrl(),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $seo['description'],
            'image' => $seo['og_image'],
            'url' => $seo['canonical'],
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => $post->author
                ? ['@type' => 'Person', 'name' => $post->author->name]
                : ['@type' => 'Organization', 'name' => config('app.name')],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
            ],
        ];

        return view('pages.blog.show', compact('post', 'relatedPosts', 'seo', 'schema'));
    }
}
