<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()
            ->with('category')
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->string('search')}%")
                    ->orWhere('short_description', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        $categories = ServiceCategory::orderBy('sort_order')->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create(): View
    {
        $service = new Service([
            'locale' => 'en',
            'visibility' => true,
            'sort_order' => 0,
        ]);

        return view('admin.services.create', [
            'service' => $service,
            ...$this->formOptions(),
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $service = Service::create($this->payload($request));

        $this->syncImage($service, $request);

        return redirect()
            ->route('admin.services.index')
            ->with('success', "Service \"{$service->name}\" created.");
    }

    public function show(Service $service): View
    {
        $service->loadMissing(['category', 'parent', 'children', 'projects', 'teamMembers', 'featuredImage']);

        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', [
            'service' => $service,
            ...$this->formOptions(),
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($this->payload($request));

        $this->syncImage($service, $request);

        return redirect()
            ->route('admin.services.index')
            ->with('success', "Service \"{$service->name}\" updated.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        $name = $service->name;

        $service->delete();

        return back()->with('success', "Service \"{$name}\" moved to trash.");
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ServiceRequest $request): array
    {
        $data = $request->safe()->except(['seo_title', 'seo_description', 'image']);

        $data['seo'] = array_filter([
            'title' => $request->input('seo_title'),
            'description' => $request->input('seo_description'),
        ]);

        return $data;
    }

    private function syncImage(Service $service, ServiceRequest $request): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $media = $service
            ->addMedia($request->file('image'))
            ->preservingOriginal()
            ->toMediaCollection('featured', 'public');

        $service->update(['featured_image_id' => $media->id]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'categories' => ServiceCategory::orderBy('sort_order')->get(),
            'parents' => Service::whereNull('parent_id')->orderBy('name')->get(['id', 'name']),
        ];
    }
}
