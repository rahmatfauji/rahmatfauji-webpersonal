<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\RollsBackTempUploads;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
{
    use RollsBackTempUploads;

    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $postId = $this->route('blog_post')?->id;

        return [
            'title'          => ['required', 'string', 'max:255'],
            'category'       => ['nullable', 'string', 'max:100'],
            'tags'           => ['nullable'],
            'slug'           => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('blog_posts', 'slug')->ignore($postId)],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'content'        => ['required', 'string', 'max:200000'],
            'featured_image' => ['nullable', 'string', 'max:2048'],
            'published_at'   => ['nullable', 'date'],
            'is_published'   => ['nullable', 'boolean'],
            'upload_token'   => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category'     => $this->input('category') !== null && trim((string) $this->input('category')) === '' ? null : $this->input('category'),
            'tags'         => $this->input('tags') !== null && trim((string) $this->input('tags')) === '' ? [] : $this->input('tags'),
            'slug'         => $this->input('slug') !== null && trim((string) $this->input('slug')) === '' ? null : $this->input('slug'),
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
