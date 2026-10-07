<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class SeoController extends Controller
{
    /**
     * @var list<string>
     */
    private array $managedKeys = [
        'seo.default_description',
        'seo.home_title',
        'seo.home_description',
        'seo.about_title',
        'seo.about_description',
        'seo.contact_title',
        'seo.contact_description',
        'seo.consultation_title',
        'seo.consultation_description',
        'seo.og_image',
    ];

    public function index(): View
    {
        $values = Setting::query()
            ->whereIn('key', $this->managedKeys)
            ->pluck('value', 'key');

        return view('admin.seo.index', [
            'fields' => collect($this->managedKeys)->map(fn (string $key) => [
                'key' => $key,
                'value' => $values[$key] ?? null,
            ])->values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:2000'],
        ]);

        foreach (Arr::only($validated['settings'], $this->managedKeys) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'seo',
                    'public' => true,
                ]
            );
        }

        return back()->with('success', 'SEO settings saved.');
    }
}
