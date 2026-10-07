<?php

namespace App\Http\Requests\Admin;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($projectId)],
            'summary' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'consultant' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 5)],
            'status' => ['required', Rule::in(['upcoming', 'ongoing', 'completed'])],
            'featured' => ['nullable', 'boolean'],
            'area' => ['nullable', 'string', 'max:100'],
            'floors' => ['nullable', 'integer', 'min:0', 'max:200'],
            'description' => ['nullable', 'string', 'max:20000'],
            'design_concept' => ['nullable', 'string', 'max:10000'],
            'architecture_info' => ['nullable', 'string', 'max:10000'],
            'structural_info' => ['nullable', 'string', 'max:10000'],
            'engineering_info' => ['nullable', 'string', 'max:10000'],
            'geotechnical_info' => ['nullable', 'string', 'max:10000'],
            'construction_info' => ['nullable', 'string', 'max:10000'],
            'interior_info' => ['nullable', 'string', 'max:10000'],
            'progress_overview' => ['nullable', 'string', 'max:10000'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
            'team_members' => ['nullable', 'array'],
            'team_members.*' => ['integer', 'exists:team_members,id'],
            'gallery' => ['nullable', 'array', 'max:10'],
            'gallery.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'featured_image_id' => ['nullable', 'integer', Rule::in($this->galleryMediaIds())],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => ($this->slug ?: '') ?: Str::slug($this->input('title')),
            'featured' => $this->boolean('featured'),
            'published_at' => $this->input('publish_now')
                ? ($this->input('published_at') ?: now()->toDateString())
                : $this->input('published_at'),
        ]);
    }

    /**
     * The featured-image picker may only point at images already in this
     * project's gallery, so the option list comes from the project's own media.
     *
     * @return array<int, int>
     */
    private function galleryMediaIds(): array
    {
        $project = $this->route('project');

        if (! $project instanceof Project) {
            return [];
        }

        return $project->images()->pluck('media_id')->all();
    }
}
