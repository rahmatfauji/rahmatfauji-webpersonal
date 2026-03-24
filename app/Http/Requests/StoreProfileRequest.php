<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\RollsBackTempUploads;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
{
    use RollsBackTempUploads;

    public function authorize(): bool
    {
        return $this->user()?->role === 'admin' && !Profile::query()->exists();
    }

    public function rules(): array
    {
        return [
            'full_name'  => ['required', 'string', 'max:255'],
            'title'      => ['required', 'string', 'max:255'],
            'bio'        => ['required', 'string', 'max:5000'],
            'email'      => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone'      => ['nullable', 'string', 'regex:/^[+\-\s()0-9]{7,25}$/', 'max:25'],
            'location'   => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:2048'],
            'github_url' => ['nullable', 'url', 'max:2048'],
            'avatar_url' => ['nullable', 'string', 'max:2048'],
            'expertise_chart' => ['required', 'array', 'min:3', 'max:8'],
            'expertise_chart.*.label' => ['required', 'string', 'max:100'],
            'expertise_chart.*.value' => ['required', 'integer', 'min:0', 'max:100'],
            'expertise_chart.*.color' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'upload_token' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $total = collect($this->input('expertise_chart', []))
                ->sum(fn ($item) => (int) ($item['value'] ?? 0));

            if ($total !== 100) {
                $validator->errors()->add('expertise_chart', __('Chart values must total 100.'));
            }
        });
    }
}
