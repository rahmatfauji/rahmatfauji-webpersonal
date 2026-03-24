<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TempUploadService
{
    public function cleanupToken(?string $token): void
    {
        if (!$this->isValidToken($token)) {
            return;
        }

        Storage::disk('public')->deleteDirectory($this->tempDirectory($token));
    }

    public function finalizeUrl(?string $url, ?string $token, string $targetDirectory): ?string
    {
        if (!$url) {
            return $url;
        }

        $tempPath = $this->extractTempPathFromUrl($url, $token);
        if (!$tempPath) {
            return $url;
        }

        $hasTempOnPublicDisk = Storage::disk('public')->exists($tempPath);
        $legacyAbsolutePath = storage_path('app/public/' . ltrim($tempPath, '/'));
        $hasTempOnLegacyPath = File::isFile($legacyAbsolutePath);

        if (!$hasTempOnPublicDisk && !$hasTempOnLegacyPath) {
            return $url;
        }

        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        $filename = (string) Str::uuid();
        $newPath = 'uploads/' . trim($targetDirectory, '/') . '/' . $filename . ($extension ? '.' . $extension : '');

        Storage::disk('public')->makeDirectory('uploads/' . trim($targetDirectory, '/'));

        if ($hasTempOnPublicDisk) {
            Storage::disk('public')->move($tempPath, $newPath);
        } else {
            Storage::disk('public')->put($newPath, File::get($legacyAbsolutePath));
            File::delete($legacyAbsolutePath);
        }

        return Storage::disk('public')->url($newPath);
    }

    public function finalizeContentUrls(?string $content, ?string $token, string $targetDirectory): ?string
    {
        if (!$content) {
            return $content;
        }

        $movedUrlMap = [];

        return preg_replace_callback('/src\s*=\s*([\"\'])(.*?)\1/i', function (array $matches) use (&$movedUrlMap, $token, $targetDirectory) {
            $originalUrl = $matches[2];

            if (isset($movedUrlMap[$originalUrl])) {
                return 'src=' . $matches[1] . $movedUrlMap[$originalUrl] . $matches[1];
            }

            $finalUrl = $this->finalizeUrl($originalUrl, $token, $targetDirectory);
            $movedUrlMap[$originalUrl] = $finalUrl ?? $originalUrl;

            return 'src=' . $matches[1] . ($finalUrl ?? $originalUrl) . $matches[1];
        }, $content);
    }

    public function tempDirectory(string $token): string
    {
        return 'uploads/tmp/' . $token;
    }

    private function isValidToken(?string $token): bool
    {
        if (!$token) {
            return false;
        }

        return (bool) preg_match('/^[A-Za-z0-9_-]{10,100}$/', $token);
    }

    private function extractTempPathFromUrl(string $url, ?string $token): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path)) {
            return null;
        }

        if (!preg_match('~/(?:storage/)?(uploads/tmp/([A-Za-z0-9_-]{10,100})/[^?#]+)~', $path, $matches)) {
            return null;
        }

        $tempPath = $matches[1] ?? null;
        $urlToken = $matches[2] ?? null;

        if (!is_string($tempPath) || !is_string($urlToken) || !$this->isValidToken($urlToken)) {
            return null;
        }

        // If request token is provided and valid, keep strict matching.
        if ($this->isValidToken($token) && $token !== $urlToken) {
            return null;
        }

        return ltrim($tempPath, '/');
    }
}