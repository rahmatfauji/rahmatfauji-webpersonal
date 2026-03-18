<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
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
            'avatar_url' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
