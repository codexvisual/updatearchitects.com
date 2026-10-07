<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <x-admin.panel title="Basics">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-ui.input name="title" label="Project Title *" :value="old('title', $project->title)" required />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.input name="slug" label="URL Slug" :value="old('slug', $project->slug)" helper="Leave blank to generate it from the title." />
                </div>

                <div class="sm:col-span-2">
                    <x-ui.textarea name="summary" label="Short Summary" rows="2" :value="old('summary', $project->summary)" helper="One or two sentences shown on listing cards." />
                </div>

                <div>
                    <x-ui.select
                        name="category"
                        label="Category"
                        :options="$categories->pluck('name', 'slug')->all()"
                        :value="old('category', $project->category)"
                        placeholder="Select a category"
                    />
                </div>

                <div>
                    <x-ui.input name="location" label="Location" :value="old('location', $project->location)" />
                </div>

                <div>
                    <x-ui.input name="area" label="Area" :value="old('area', $project->area)" placeholder="e.g. 1,550 sq.ft" />
                </div>

                <div>
                    <x-ui.input name="floors" type="number" min="0" label="Floors" :value="old('floors', $project->floors)" />
                </div>

                <div>
                    <x-ui.input name="year" type="number" min="1900" label="Year" :value="old('year', $project->year)" />
                </div>

                <div>
                    <x-ui.input name="client" label="Client" :value="old('client', $project->client)" />
                </div>

                <div>
                    <x-ui.input name="consultant" label="Consultant" :value="old('consultant', $project->consultant)" />
                </div>

                <div>
                    <x-ui.select
                        name="status"
                        label="Status *"
                        :options="['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed']"
                        :value="old('status', $project->status)"
                        :placeholder="null"
                        required
                    />
                </div>

                <div>
                    <x-ui.select
                        name="locale"
                        label="Language *"
                        :options="['en' => 'English', 'bn' => 'বাংলা (Bangla)']"
                        :value="old('locale', $project->locale)"
                        :placeholder="null"
                        required
                    />
                </div>
            </div>
        </x-admin.panel>

        <x-admin.panel title="Description">
            <x-ui.textarea name="description" label="Overview" rows="8" :value="old('description', $project->description)" />
        </x-admin.panel>

        <x-admin.panel title="Scope Details" subtitle="Only the filled-in sections appear on the public project page.">
            <div class="space-y-5">
                <x-ui.textarea name="design_concept" label="Design Concept" rows="3" :value="old('design_concept', $project->design_concept)" />
                <x-ui.textarea name="architecture_info" label="Architecture" rows="3" :value="old('architecture_info', $project->architecture_info)" />
                <x-ui.textarea name="structural_info" label="Structural Engineering" rows="3" :value="old('structural_info', $project->structural_info)" />
                <x-ui.textarea name="engineering_info" label="Engineering Services" rows="3" :value="old('engineering_info', $project->engineering_info)" />
                <x-ui.textarea name="geotechnical_info" label="Geotechnical" rows="3" :value="old('geotechnical_info', $project->geotechnical_info)" />
                <x-ui.textarea name="construction_info" label="Construction" rows="3" :value="old('construction_info', $project->construction_info)" />
                <x-ui.textarea name="interior_info" label="Interior" rows="3" :value="old('interior_info', $project->interior_info)" />
                <x-ui.textarea name="progress_overview" label="Progress Overview (internal)" rows="3" :value="old('progress_overview', $project->progress_overview)" />
            </div>
        </x-admin.panel>

        <x-admin.panel title="SEO">
            <div class="space-y-5">
                <x-ui.input name="seo_title" label="Meta Title" :value="old('seo_title', $project->seo['title'] ?? null)" helper="Defaults to the project title." />
                <x-ui.textarea name="seo_description" label="Meta Description" rows="2" :value="old('seo_description', $project->seo['description'] ?? null)" helper="Around 155 characters works best." />
            </div>
        </x-admin.panel>
    </div>

    <div class="space-y-6">
        <x-admin.panel title="Publishing">
            <div class="space-y-5">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="featured" value="1" class="mt-1 w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500" @checked(old('featured', $project->featured))>
                    <span>
                        <span class="block text-body-sm font-medium text-stone-900 dark:text-white">Feature on the homepage</span>
                        <span class="block text-caption text-stone-500 dark:text-stone-400">Shows in the homepage featured project section.</span>
                    </span>
                </label>

                <x-ui.input
                    type="date"
                    name="published_at"
                    label="Publish date"
                    :value="old('published_at', $project->published_at?->toDateString())"
                    helper="Leave empty to keep the project as a draft."
                />

                <x-ui.input type="number" min="0" name="sort_order" label="Sort order" :value="old('sort_order', $project->sort_order ?? 0)" />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Services">
            <fieldset class="space-y-2">
                @foreach($allServices as $service)
                    <label class="flex items-center gap-2 text-body-sm text-stone-700 dark:text-stone-300">
                        <input
                            type="checkbox"
                            name="services[]"
                            value="{{ $service->id }}"
                            class="w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500"
                            @checked(in_array($service->id, old('services', $project->services->pluck('id')->all()), false))
                        >
                        {{ $service->name }}
                    </label>
                @endforeach
            </fieldset>
        </x-admin.panel>

        <x-admin.panel title="Project Team">
            <fieldset class="space-y-2">
                @forelse($allTeamMembers as $member)
                    <label class="flex items-center gap-2 text-body-sm text-stone-700 dark:text-stone-300">
                        <input
                            type="checkbox"
                            name="team_members[]"
                            value="{{ $member->id }}"
                            class="w-4 h-4 rounded border-stone-300 text-accent-600 focus:ring-accent-500"
                            @checked(in_array($member->id, old('team_members', $project->teamMembers->pluck('id')->all()), false))
                        >
                        {{ $member->name }}
                    </label>
                @empty
                    <p class="text-body-sm text-stone-500">No team members yet.</p>
                @endforelse
            </fieldset>
        </x-admin.panel>

        <x-admin.panel title="Gallery Images">
            @if($galleryMedia->isNotEmpty())
                <div class="mb-5">
                    <x-ui.select
                        name="featured_image_id"
                        label="Featured image"
                        :options="$galleryMedia->mapWithKeys(fn ($image) => [$image->media_id => $image->media?->file_name ?: ('Image #'.$image->media_id)])->all()"
                        :value="old('featured_image_id', $project->featured_image_id)"
                        helper="Used as the large image on project listings and at the top of the project page."
                    />
                </div>
            @endif

            <x-forms.file-upload
                name="gallery[]"
                label="Add images"
                helper="JPEG, PNG or WebP up to 10 MB each."
                :multiple="true"
                :acceptedTypes="['jpg', 'jpeg', 'png', 'webp']"
                :maxSizeMB="10"
            />
        </x-admin.panel>

        <x-admin.panel>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">
                    {{ $project->exists ? 'Save changes' : 'Create project' }}
                </button>
                <a href="{{ route('admin.projects.index') }}" class="btn-ghost w-full">Cancel</a>
            </div>
        </x-admin.panel>
    </div>
</div>
