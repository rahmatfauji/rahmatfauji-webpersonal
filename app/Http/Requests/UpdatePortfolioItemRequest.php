<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\RollsBackTempUploads;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioItemRequest extends FormRequest
{
    use RollsBackTempUploads;

    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'category'      => ['required', 'string', 'max:100'],
            'summary'       => ['required', 'string', 'max:500'],
            'description'   => ['nullable', 'string', 'max:100000'],
            'project_url'   => [
                'nullable',
                'string',
                'max:2048',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (!is_string($value) || $value === '') {
                        return;
                    }

                    if (filter_var($value, FILTER_VALIDATE_URL) || str_starts_with($value, '/')) {
                        return;
                    }

                    $fail(__('The :attribute must be a valid URL or start with /.', ['attribute' => $attribute]));
                },
            ],
            'image_url'     => ['nullable', 'string', 'max:2048'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'     => ['nullable', 'boolean'],
            'upload_token'  => ['nullable', 'string'],
        ];
    }
}
