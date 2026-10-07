<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
        $serviceId = $this->route('service')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')->ignore($serviceId)],
            'category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'parent_id' => ['nullable', 'integer', 'exists:services,id', Rule::notIn([$serviceId])],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'full_description' => ['nullable', 'string', 'max:20000'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => ($this->slug ?: '') ?: Str::slug($this->input('name')),
            'visibility' => $this->boolean('visibility'),
        ]);
    }
}
