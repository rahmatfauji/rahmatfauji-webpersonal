<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\RollsBackTempUploads;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogPostRequest extends FormRequest
{
    use RollsBackTempUploads;

    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:blog_posts,slug'],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'content'        => ['required', 'string', 'max:200000'],
            'featured_image' => ['nullable', 'url', 'max:2048'],
            'published_at'   => ['nullable', 'date'],
            'is_published'   => ['nullable', 'boolean'],
            'upload_token'   => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug'         => $this->input('slug') !== null && trim((string) $this->input('slug')) === '' ? null : $this->input('slug'),
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
