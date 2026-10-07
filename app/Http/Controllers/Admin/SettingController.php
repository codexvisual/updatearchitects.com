<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $settings = Setting::query()
            ->when($request->filled('group'), fn ($query) => $query->where('group', $request->string('group')))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('key', 'like', "%{$request->string('search')}%")
                    ->orWhere('description', 'like', "%{$request->string('search')}%");
            }))
            ->orderBy('group')
            ->orderBy('key')
            ->paginate(25)
            ->withQueryString();

        $groups = Setting::query()
            ->select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable'],
        ]);

        $normalized = [];

        foreach (Arr::dot($validated['settings'] ?? []) as $key => $value) {
            $normalized[$key] = $this->normalizeValue($key, $value);
        }

        foreach ($normalized as $key => $value) {
            Setting::query()->where('key', $key)->first()?->update(['value' => $value]);
        }

        Setting::flushResolvedMaps();

        return back()->with('success', 'Settings saved.');
    }

    /**
     * Array-shaped settings are posted back as JSON textareas, so decode those
     * before saving to keep the stored type identical to what was displayed.
     *
     * @throws ValidationException
     */
    private function normalizeValue(string $key, mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = trim($value);

        if ($trimmed === '' || ! in_array($trimmed[0], ['{', '['], true)) {
            return $value;
        }

        try {
            return json_decode($trimmed, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw ValidationException::withMessages([
                "settings.{$key}" => 'This value must be valid JSON, or start with plain text.',
            ]);
        }
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return back()->with('success', "Setting \"{$setting->key}\" removed.");
    }
}
