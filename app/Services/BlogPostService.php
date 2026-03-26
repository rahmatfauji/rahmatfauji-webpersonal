<?php

namespace App\Services;

use App\Contracts\BlogPostRepositoryInterface;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogPostService
{
    public function __construct(
        private BlogPostRepositoryInterface $blogPostRepository,
        private BlogSlugService $blogSlugService
    ) {
    }

    public function create(array $payload): BlogPost
    {
        $attributes = $this->prepareAttributes($payload);

        return $this->blogPostRepository->create($attributes);
    }

    public function update(BlogPost $blogPost, array $payload): bool
    {
        $attributes = $this->prepareAttributes($payload, $blogPost->id);

        return $this->blogPostRepository->update($blogPost, $attributes);
    }

    private function prepareAttributes(array $payload, ?int $ignoreId = null): array
    {
        $payload['slug'] = $this->blogSlugService->generate(
            $payload['title'],
            $payload['slug'] ?? null,
            $ignoreId
        );

        $payload['excerpt'] = $payload['excerpt'] ?: Str::limit(strip_tags($payload['content']), 170);
        $payload['category'] = isset($payload['category']) && is_string($payload['category'])
            ? trim($payload['category']) ?: null
            : null;
        $payload['tags'] = $this->normalizeTags($payload['tags'] ?? []);

        return $payload;
    }

    private function normalizeTags(array|string|null $tags): array
    {
        if (is_string($tags)) {
            $tags = preg_split('/\s*,\s*/', $tags) ?: [];
        }

        if (!is_array($tags)) {
            return [];
        }

        return collect($tags)
            ->map(fn ($tag) => is_string($tag) ? trim($tag) : '')
            ->filter()
            ->map(fn (string $tag) => Str::title($tag))
            ->unique(fn (string $tag) => Str::lower($tag))
            ->values()
            ->all();
    }
}
