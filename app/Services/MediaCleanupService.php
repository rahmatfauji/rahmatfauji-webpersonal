<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\PortfolioItem;
use App\Models\Slide;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaCleanupService
{
    public function cleanupBlogPostAssets(BlogPost $blogPost): void
    {
        $this->deleteByUrl($blogPost->featured_image);

        foreach ($this->extractImageUrlsFromHtml((string) ($blogPost->content ?? '')) as $url) {
            $this->deleteByUrl($url);
        }
    }

    public function cleanupPortfolioItemAssets(PortfolioItem $portfolioItem): void
    {
        $this->deleteByUrl($portfolioItem->image_url);
    }

    public function cleanupSlideAssets(Slide $slide): void
    {
        $this->deleteByUrl($slide->image_url);
    }

    public function deleteByUrl(?string $url): void
    {
        $storagePath = $this->extractStoragePath($url);

        if (!$storagePath) {
            return;
        }

        $publicDisk = Storage::disk('public');

        if ($publicDisk->exists($storagePath)) {
            $publicDisk->delete($storagePath);
        }

        $legacyAbsolutePath = storage_path('app/public/' . ltrim($storagePath, '/'));
        if (File::isFile($legacyAbsolutePath)) {
            File::delete($legacyAbsolutePath);
        }
    }

    private function extractImageUrlsFromHtml(string $html): array
    {
        if ($html === '') {
            return [];
        }

        preg_match_all('/src\s*=\s*(["\'])(.*?)\1/i', $html, $matches);

        $urls = $matches[2] ?? [];
        if (!is_array($urls)) {
            return [];
        }

        return array_values(array_unique(array_filter($urls, fn ($url) => is_string($url) && $url !== '')));
    }

    private function extractStoragePath(?string $url): ?string
    {
        if (!is_string($url) || trim($url) === '') {
            return null;
        }

        $trimmedUrl = trim($url);
        $path = parse_url($trimmedUrl, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            $path = $trimmedUrl;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'storage/uploads/')) {
            return substr($normalized, strlen('storage/'));
        }

        if (str_starts_with($normalized, 'uploads/')) {
            return $normalized;
        }

        return null;
    }
}
