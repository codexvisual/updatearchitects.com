<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OfficeController extends Controller
{
    public function index(): View
    {
        $offices = Office::query()
            ->withCount('teamMembers')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.offices.index', compact('offices'));
    }

    public function create(): View
    {
        $office = new Office(['visibility' => true, 'locale' => 'en', 'sort_order' => 0]);

        return view('admin.offices.create', compact('office'));
    }

    public function store(Request $request): RedirectResponse
    {
        $office = Office::create($this->validated($request));

        return redirect()
            ->route('admin.offices.index')
            ->with('success', "Office \"{$office->name}\" created.");
    }

    public function show(Office $office): View
    {
        $office->load('teamMembers');

        return view('admin.offices.show', compact('office'));
    }

    public function edit(Office $office): View
    {
        return view('admin.offices.edit', compact('office'));
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $office->update($this->validated($request, $office));

        return redirect()
            ->route('admin.offices.index')
            ->with('success', "Office \"{$office->name}\" updated.");
    }

    public function destroy(Office $office): RedirectResponse
    {
        $name = $office->name;

        $office->delete();

        return back()->with('success', "Office \"{$name}\" moved to trash.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Office $office = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('offices', 'slug')->ignore($office?->id)],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'map_embed' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['name']);
        $validated['visibility'] = $request->boolean('visibility');

        return $validated;
    }
}
