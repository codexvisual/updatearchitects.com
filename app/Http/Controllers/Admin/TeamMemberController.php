<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberRequest;
use App\Models\Office;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $team = TeamMember::query()
            ->with(['photo', 'office'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->string('search')}%")
                    ->orWhere('designation', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('office'), fn ($query) => $query->where('office_id', $request->integer('office')))
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        $offices = Office::orderBy('sort_order')->get();

        return view('admin.team.index', compact('team', 'offices'));
    }

    public function create(): View
    {
        $member = new TeamMember([
            'locale' => 'en',
            'visibility' => true,
            'sort_order' => 0,
        ]);

        return view('admin.team.create', [
            'member' => $member,
            'offices' => Office::orderBy('sort_order')->get(),
        ]);
    }

    public function store(TeamMemberRequest $request): RedirectResponse
    {
        $member = TeamMember::create($this->payload($request));

        $this->syncPhoto($member, $request);

        return redirect()
            ->route('admin.team.index')
            ->with('success', "\"{$member->name}\" added to the team.");
    }

    public function show(TeamMember $member): View
    {
        $member->loadMissing(['photo', 'office', 'projects', 'services']);

        return view('admin.team.show', compact('member'));
    }

    public function edit(TeamMember $member): View
    {
        return view('admin.team.edit', [
            'member' => $member,
            'offices' => Office::orderBy('sort_order')->get(),
        ]);
    }

    public function update(TeamMemberRequest $request, TeamMember $member): RedirectResponse
    {
        $member->update($this->payload($request));

        $this->syncPhoto($member, $request);

        return redirect()
            ->route('admin.team.index')
            ->with('success', "\"{$member->name}\" updated.");
    }

    public function destroy(TeamMember $member): RedirectResponse
    {
        $name = $member->name;

        $member->delete();

        return back()->with('success', "\"{$name}\" moved to trash.");
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(TeamMemberRequest $request): array
    {
        return $request->safe()->except(['photo']);
    }

    private function syncPhoto(TeamMember $member, TeamMemberRequest $request): void
    {
        if (! $request->hasFile('photo')) {
            return;
        }

        $media = $member
            ->addMedia($request->file('photo'))
            ->preservingOriginal()
            ->toMediaCollection('photo', 'public');

        $member->update(['photo_id' => $media->id]);
    }
}
