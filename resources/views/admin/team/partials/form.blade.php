<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <x-admin.panel title="Profile">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="name" label="Full Name *" :value="old('name', $member->name)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="slug" label="URL Slug" :value="old('slug', $member->slug)" helper="Leave blank to generate it from the name." />
                </div>

                <div>
                    <x-ui.input name="designation" label="Designation" :value="old('designation', $member->designation)" />
                </div>

                <div>
                    <x-ui.input name="registration" label="Registration" :value="old('registration', $member->registration)" placeholder="e.g. IEB-12345" />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="qualification" label="Qualifications" rows="2" :value="old('qualification', $member->qualification)" />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="expertise" label="Expertise" rows="2" :value="old('expertise', $member->expertise)" />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="biography" label="Biography" rows="8" :value="old('biography', $member->biography)" />
                </div>
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Contact">
            <div class="space-y-5">
                <x-ui.input type="email" name="email" label="Email" :value="old('email', $member->email)" />
                <x-ui.input type="tel" name="phone" label="Phone" :value="old('phone', $member->phone)" />
                <x-ui.select
                    name="office_id"
                    label="Office"
                    :options="$offices->pluck('name', 'id')->all()"
                    :value="old('office_id', $member->office_id)"
                    placeholder="Not assigned"
                />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Photo">
            @if($member->photo)
                <img src="{{ $member->photo->getAvailableUrl(['thumbnail']) }}" alt="{{ $member->name }}" class="mx-auto mb-4 h-32 w-32 rounded-full object-cover" loading="lazy">
            @endif

            <x-forms.file-upload
                name="photo"
                label="Upload photo"
                helper="JPEG, PNG or WebP up to 5 MB. Square images work best."
                :multiple="false"
                :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                :maxSizeMB="5"
            />
        </x-admin.panel>

        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="visibility" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('visibility', $member->visibility ?? true))>
                    <span class="text-body-sm font-medium text-stone-900 dark:text-white">Visible on the website</span>
                </label>

                <x-ui.select
                    name="locale"
                    label="Language *"
                    :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                    :value="old('locale', $member->locale)"
                    :placeholder="null"
                    required
                />

                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $member->sort_order ?? 0)" />
            </div>
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">
                    {{ $member->exists ? 'Save changes' : 'Add member' }}
                </button>
                <a href="{{ route('admin.team.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
