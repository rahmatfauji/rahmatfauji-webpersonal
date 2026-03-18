<?php

namespace App\Repositories;

use App\Contracts\BlogPostRepositoryInterface;
use App\Models\BlogPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentBlogPostRepository implements BlogPostRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return BlogPost::query()->latest()->paginate($perPage);
    }

    public function create(array $attributes): BlogPost
    {
        return BlogPost::query()->create($attributes);
    }

    public function update(BlogPost $blogPost, array $attributes): bool
    {
        return $blogPost->update($attributes);
    }

    public function delete(BlogPost $blogPost): bool
    {
        return (bool) $blogPost->delete();
    }
}
