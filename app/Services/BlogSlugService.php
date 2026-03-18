<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogSlugService
{
    public function generate(string $title, ?string $customSlug = null, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug(trim($customSlug ?: $title));
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'artikel';

        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return BlogPost::query()
            ->when($ignoreId, static fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists();
    }
}
