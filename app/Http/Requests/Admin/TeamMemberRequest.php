<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamMemberRequest extends FormRequest
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
        $memberId = $this->route('member')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('team_members', 'slug')->ignore($memberId)],
            'designation' => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:2000'],
            'expertise' => ['nullable', 'string', 'max:2000'],
            'registration' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string', 'max:20000'],
            'office_id' => ['nullable', 'integer', 'exists:offices,id'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'locale' => ['required', Rule::in(['en', 'bn'])],
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
