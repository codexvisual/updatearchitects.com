<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationLead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConsultationLeadController extends Controller
{
    /**
     * @var list<string>
     */
    private const STATUSES = ['new', 'contacted', 'qualified', 'proposal_sent', 'won', 'lost'];

    public function index(Request $request): View
    {
        $leads = ConsultationLead::query()
            ->with('assignee')
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->string('search')}%")
                    ->orWhere('email', 'like', "%{$request->string('search')}%")
                    ->orWhere('phone', 'like', "%{$request->string('search')}%")
                    ->orWhere('project_location', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.consultations.index', compact('leads'));
    }

    public function create(): View
    {
        return view('admin.consultations.create', [
            'lead' => new ConsultationLead(['status' => 'new']),
            'statuses' => self::STATUSES,
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $lead = ConsultationLead::create($this->validated($request) + ['status' => 'new']);

        $lead->statusHistory()->create([
            'from_status' => null,
            'to_status' => 'new',
            'note' => 'Lead created manually in the admin',
        ]);

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', "Lead \"{$lead->name}\" created.");
    }

    public function show(ConsultationLead $lead): View
    {
        $lead->load(['assignee', 'statusHistory.user']);

        return view('admin.consultations.show', compact('lead'));
    }

    public function edit(ConsultationLead $lead): View
    {
        return view('admin.consultations.edit', [
            'lead' => $lead,
            'statuses' => self::STATUSES,
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, ConsultationLead $lead): RedirectResponse
    {
        $previousStatus = $lead->status;

        $lead->update($this->validated($request, $lead));

        if ($lead->status !== $previousStatus) {
            $lead->statusHistory()->create([
                'from_status' => $previousStatus,
                'to_status' => $lead->status,
                'user_id' => $request->user()->id,
                'note' => 'Status changed in the admin',
            ]);
        }

        return redirect()
            ->route('admin.consultations.show', $lead)
            ->with('success', "Lead \"{$lead->name}\" updated.");
    }

    public function destroy(ConsultationLead $lead): RedirectResponse
    {
        $name = $lead->name;

        $lead->delete();

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', "Lead \"{$name}\" deleted.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?ConsultationLead $lead = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'project_type' => ['nullable', 'string', 'max:100'],
            'project_location' => ['nullable', 'string', 'max:255'],
            'approximate_area' => ['nullable', 'string', 'max:100'],
            'required_services' => ['nullable', 'array'],
            'required_services.*' => ['string', 'max:100'],
            'estimated_budget' => ['nullable', 'string', 'max:100'],
            'expected_start_date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(self::STATUSES)],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'follow_up_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ]);
    }
}
