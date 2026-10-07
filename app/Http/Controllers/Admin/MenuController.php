<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::query()
            ->withCount('items')
            ->orderBy('slug')
            ->paginate(25);

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('admin.menus.create', ['menu' => new Menu(['locale' => 'en'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $menu = Menu::create($this->validated($request));

        return redirect()
            ->route('admin.menus.show', $menu)
            ->with('success', "Menu \"{$menu->name}\" created.");
    }

    public function show(Menu $menu): View
    {
        $menu->load('items.children');

        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $menu->update($this->validated($request, $menu));

        return redirect()
            ->route('admin.menus.show', $menu)
            ->with('success', "Menu \"{$menu->name}\" updated.");
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $name = $menu->name;

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', "Menu \"{$name}\" removed.");
    }

    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        $item = $menu->items()->create($this->validatedItem($request));

        return back()->with('success', "Menu item \"{$item->title}\" added.");
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_unless($item->menu_id === $menu->id, 404);

        $item->update($this->validatedItem($request));

        return back()->with('success', "Menu item \"{$item->title}\" updated.");
    }

    public function destroyItem(Menu $menu, MenuItem $item): RedirectResponse
    {
        abort_unless($item->menu_id === $menu->id, 404);

        $item->delete();

        return back()->with('success', 'Menu item removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Menu $menu = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('menus', 'slug')->ignore($menu?->id)],
            'locale' => ['required', Rule::in(['en', 'bn'])],
        ]);

        $validated['slug'] = ($validated['slug'] ?? '') ?: Str::slug($validated['name']);

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedItem(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['custom', 'route', 'page'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['visibility'] = $request->boolean('visibility');
        $validated['locale'] = app()->getLocale();

        return $validated;
    }
}
