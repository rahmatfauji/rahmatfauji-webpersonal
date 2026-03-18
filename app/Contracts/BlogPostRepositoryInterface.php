<?php

namespace App\Contracts;

use App\Models\BlogPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BlogPostRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function create(array $attributes): BlogPost;

    public function update(BlogPost $blogPost, array $attributes): bool;

    public function delete(BlogPost $blogPost): bool;
}
