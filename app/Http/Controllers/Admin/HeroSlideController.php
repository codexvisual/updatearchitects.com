<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(Request $request): View
    {
        $slides = HeroSlide::query()
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->string('search')}%")
                    ->orWhere('subtitle', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('locale'), fn ($query) => $query->where('locale', $request->string('locale')))
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create(): View
    {
        $slide = new HeroSlide([
            'locale' => 'en',
            'visibility' => true,
            'sort_order' => 0,
        ]);

        return view('admin.hero-slides.create', compact('slide'));
    }

    public function store(HeroSlideRequest $request): RedirectResponse
    {
        $slide = HeroSlide::create($this->payload($request));

        $this->syncImages($slide, $request);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', "Slide \"{$slide->title}\" created.");
    }

    public function edit(HeroSlide $heroSlide): View
    {
        $slide = $heroSlide;

        return view('admin.hero-slides.edit', compact('slide'));
    }

    public function update(HeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update($this->payload($request));

        $this->syncImages($heroSlide, $request);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', "Slide \"{$heroSlide->title}\" updated.");
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $title = $heroSlide->title;

        $heroSlide->delete();

        return back()->with('success', "Slide \"{$title}\" moved to trash.");
    }

    public function toggle(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update(['visibility' => ! $heroSlide->visibility]);

        $label = $heroSlide->visibility ? 'activated' : 'deactivated';

        return back()->with('success', "Slide \"{$heroSlide->title}\" {$label}.");
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(HeroSlideRequest $request): array
    {
        return $request->safe()->except(['image', 'mobile_image']);
    }

    private function syncImages(HeroSlide $slide, HeroSlideRequest $request): void
    {
        if ($request->hasFile('image')) {
            $media = $slide
                ->addMedia($request->file('image'))
                ->preservingOriginal()
                ->toMediaCollection('featured', 'public');

            $slide->update(['image_id' => $media->id]);
        }

        if ($request->hasFile('mobile_image')) {
            $media = $slide
                ->addMedia($request->file('mobile_image'))
                ->preservingOriginal()
                ->toMediaCollection('featured_mobile', 'public');

            $slide->update(['mobile_image_id' => $media->id]);
        }
    }
}
