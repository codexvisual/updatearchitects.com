<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * @var list<string>
     */
    private const STATUSES = ['unread', 'read', 'replied', 'archived'];

    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->string('search')}%")
                    ->orWhere('email', 'like', "%{$request->string('search')}%")
                    ->orWhere('message', 'like', "%{$request->string('search')}%");
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contacts.index', compact('messages'));
    }

    public function create(): View
    {
        return view('admin.contacts.create', ['message' => new ContactMessage(['status' => 'unread'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $message = ContactMessage::create($this->validated($request) + ['status' => 'unread']);

        return redirect()
            ->route('admin.contacts.show', $message)
            ->with('success', "Message from \"{$message->name}\" saved.");
    }

    public function show(ContactMessage $message): View
    {
        if ($message->status === 'unread') {
            $message->update(['status' => 'read', 'read_at' => now()]);
        }

        return view('admin.contacts.show', compact('message'));
    }

    public function edit(ContactMessage $message): View
    {
        return view('admin.contacts.edit', [
            'message' => $message,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, ContactMessage $message): RedirectResponse
    {
        $message->update($this->validated($request));

        return redirect()
            ->route('admin.contacts.show', $message)
            ->with('success', 'Message updated.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Message deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $message = $this->route('contact_message');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            'status' => ['nullable', Rule::in(self::STATUSES)],
        ]);

        if (($data['status'] ?? null) === 'replied' && ! $message?->replied_at) {
            $data['replied_at'] = now();
        }

        return $data;
    }
}
