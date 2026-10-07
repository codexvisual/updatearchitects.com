<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <x-admin.panel title="Message">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.input name="name" label="Name *" :value="old('name', $message->name)" required />
                <x-ui.input type="email" name="email" label="Email *" :value="old('email', $message->email)" required />
                <x-ui.input type="tel" name="phone" label="Phone" :value="old('phone', $message->phone)" />
                <x-ui.input name="subject" label="Subject" :value="old('subject', $message->subject)" />
                <div class="sm:col-span-2">
                    <x-ui.textarea name="message" label="Message *" rows="8" :value="old('message', $message->message)" required />
                </div>
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Status">
            <x-ui.select
                name="status"
                label="Status"
                :options="collect($statuses ?? ['unread', 'read', 'replied', 'archived'])->mapWithKeys(fn ($status) => [$status => ucfirst($status)])->all()"
                :value="old('status', $message->status)"
            />
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">{{ $message->exists ? 'Save message' : 'Create message' }}</button>
                <a href="{{ route('admin.contacts.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
