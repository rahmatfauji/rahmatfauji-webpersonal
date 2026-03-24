<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class RefreshContentCommand extends Command
{
    protected $signature = 'content:refresh {--keep-temp : Keep uploads/tmp directory untouched}';

    protected $description = 'Clean upload files and reseed profile, blog, portfolio, and slideshow content.';

    public function handle(): int
    {
        $this->info('Cleaning upload files...');

        $removed = $this->cleanupUploadFiles((bool) $this->option('keep-temp'));
        $this->line('Removed files: ' . $removed);

        $this->info('Running DatabaseSeeder...');
        Artisan::call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => true,
        ]);

        $this->line(trim((string) Artisan::output()));
        $this->info('Content refresh complete.');

        return self::SUCCESS;
    }

    private function cleanupUploadFiles(bool $keepTemp): int
    {
        $removed = 0;

        $disk = Storage::disk('public');
        $directories = $disk->directories('uploads');

        foreach ($directories as $directory) {
            if ($keepTemp && $directory === 'uploads/tmp') {
                continue;
            }

            $files = $disk->allFiles($directory);
            if ($files === []) {
                continue;
            }

            $disk->delete($files);
            $removed += count($files);
        }

        $legacyFiles = $disk->files('uploads/blog-inline');
        if ($legacyFiles !== []) {
            $disk->delete($legacyFiles);
            $removed += count($legacyFiles);
        }

        return $removed;
    }
}
