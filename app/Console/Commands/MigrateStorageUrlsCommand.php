<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\PortfolioItem;
use App\Models\Profile;
use App\Models\Slide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateStorageUrlsCommand extends Command
{
    protected $signature = 'storage:migrate-urls
                            {--dry-run : Preview changes without writing to database}
                            {--chunk=200 : Number of rows processed per chunk}';

    protected $description = 'Migrate stored image URLs to the configured public disk URL (PUBLIC_STORAGE_URL/PUBLIC_STORAGE_PATH).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $chunkSize = max(1, (int) $this->option('chunk'));
        $targetBaseUrl = $this->resolvePublicDiskBaseUrl();

        $this->info('Target storage base URL: ' . $targetBaseUrl);
        if ($dryRun) {
            $this->warn('Dry-run mode enabled. No database changes will be saved.');
        }

        $summary = [
            'profile.avatar_url' => $this->migrateSingleColumn(Profile::class, 'avatar_url', $targetBaseUrl, $chunkSize, $dryRun),
            'portfolio_items.image_url' => $this->migrateSingleColumn(PortfolioItem::class, 'image_url', $targetBaseUrl, $chunkSize, $dryRun),
            'slides.image_url' => $this->migrateSingleColumn(Slide::class, 'image_url', $targetBaseUrl, $chunkSize, $dryRun),
            'blog_posts.featured_image' => $this->migrateSingleColumn(BlogPost::class, 'featured_image', $targetBaseUrl, $chunkSize, $dryRun),
            'blog_posts.content' => $this->migrateBlogContent($targetBaseUrl, $chunkSize, $dryRun),
        ];

        $this->newLine();
        $this->info('Migration summary:');
        foreach ($summary as $field => $count) {
            $this->line(str_pad($field, 30) . ' : ' . $count . ' row(s) changed');
        }

        $total = array_sum($summary);
        $this->newLine();
        if ($dryRun) {
            $this->info('Dry-run complete. Total rows that would change: ' . $total);
        } else {
            $this->info('Migration complete. Total rows changed: ' . $total);
        }

        return self::SUCCESS;
    }

    private function migrateSingleColumn(string $modelClass, string $column, string $targetBaseUrl, int $chunkSize, bool $dryRun): int
    {
        $changedRows = 0;

        $modelClass::query()
            ->select(['id', $column])
            ->whereNotNull($column)
            ->orderBy('id')
            ->chunkById($chunkSize, function ($rows) use (&$changedRows, $modelClass, $column, $targetBaseUrl, $dryRun): void {
                foreach ($rows as $row) {
                    $currentValue = (string) $row->{$column};
                    $nextValue = $this->rewriteStorageUrl($currentValue, $targetBaseUrl);

                    if ($nextValue === $currentValue) {
                        continue;
                    }

                    $changedRows++;

                    if (!$dryRun) {
                        $modelClass::query()->whereKey($row->id)->update([$column => $nextValue]);
                    }
                }
            });

        return $changedRows;
    }

    private function migrateBlogContent(string $targetBaseUrl, int $chunkSize, bool $dryRun): int
    {
        $changedRows = 0;

        BlogPost::query()
            ->select(['id', 'content'])
            ->whereNotNull('content')
            ->orderBy('id')
            ->chunkById($chunkSize, function ($rows) use (&$changedRows, $targetBaseUrl, $dryRun): void {
                foreach ($rows as $row) {
                    $content = (string) $row->content;
                    $nextContent = $this->rewriteStorageUrlsInText($content, $targetBaseUrl);

                    if ($nextContent === $content) {
                        continue;
                    }

                    $changedRows++;

                    if (!$dryRun) {
                        BlogPost::query()->whereKey($row->id)->update(['content' => $nextContent]);
                    }
                }
            });

        return $changedRows;
    }

    private function rewriteStorageUrlsInText(string $text, string $targetBaseUrl): string
    {
        return (string) preg_replace_callback(
            '~https?://[^\s"\'<>]+|/storage/[^\s"\'<>]+|storage/[^\s"\'<>]+|/uploads/blog-inline/[^\s"\'<>]+|uploads/blog-inline/[^\s"\'<>]+~i',
            fn (array $matches): string => $this->rewriteStorageUrl($matches[0], $targetBaseUrl),
            $text
        );
    }

    private function rewriteStorageUrl(string $value, string $targetBaseUrl): string
    {
        $trimmed = trim($value);
        if ($trimmed === '' || str_starts_with($trimmed, 'data:')) {
            return $value;
        }

        $query = parse_url($trimmed, PHP_URL_QUERY);
        $fragment = parse_url($trimmed, PHP_URL_FRAGMENT);

        $relative = $this->extractRelativeUploadPath($trimmed);
        if ($relative === null) {
            return $value;
        }

        $rebuilt = rtrim($targetBaseUrl, '/') . '/' . ltrim($relative, '/');

        if (is_string($query) && $query !== '') {
            $rebuilt .= '?' . $query;
        }

        if (is_string($fragment) && $fragment !== '') {
            $rebuilt .= '#' . $fragment;
        }

        return $rebuilt;
    }

    private function extractRelativeUploadPath(string $urlOrPath): ?string
    {
        $path = parse_url($urlOrPath, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = $urlOrPath;
        }

        $storagePos = strpos($path, '/storage/');
        if ($storagePos !== false) {
            $relative = ltrim(substr($path, $storagePos + strlen('/storage/')), '/');
            return str_starts_with($relative, 'uploads/') ? $relative : null;
        }

        if (str_starts_with($path, '/storage/')) {
            $relative = ltrim(substr($path, strlen('/storage/')), '/');
            return str_starts_with($relative, 'uploads/') ? $relative : null;
        }

        if (str_starts_with($path, 'storage/')) {
            $relative = ltrim(substr($path, strlen('storage/')), '/');
            return str_starts_with($relative, 'uploads/') ? $relative : null;
        }

        $legacyPos = strpos($path, '/uploads/blog-inline/');
        if ($legacyPos !== false) {
            $relativeLegacy = ltrim(substr($path, $legacyPos + 1), '/');
            return str_starts_with($relativeLegacy, 'uploads/blog-inline/') ? $relativeLegacy : null;
        }

        if (str_starts_with($path, 'uploads/blog-inline/')) {
            return $path;
        }

        return null;
    }

    private function resolvePublicDiskBaseUrl(): string
    {
        $marker = '__storage_url_marker__';
        $markedUrl = Storage::disk('public')->url($marker);
        $markerPos = strrpos($markedUrl, '/' . $marker);

        if ($markerPos === false) {
            return rtrim($markedUrl, '/');
        }

        return rtrim(substr($markedUrl, 0, $markerPos), '/');
    }
}
