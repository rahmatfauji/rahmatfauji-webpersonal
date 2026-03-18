<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'subtitle'      => ['nullable', 'string', 'max:500'],
            'image_url'     => ['required', 'url', 'max:2048'],
            'button_text'   => ['nullable', 'string', 'max:100'],
            'button_url'    => ['nullable', 'url', 'max:2048'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }
}
