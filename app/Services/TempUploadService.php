<?php

namespace App\Services;

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
        if (!$url || !$this->isValidToken($token)) {
            return $url;
        }

        $tempPath = $this->extractTempPathFromUrl($url, $token);
        if (!$tempPath || !Storage::disk('public')->exists($tempPath)) {
            return $url;
        }

        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        $filename = (string) Str::uuid();
        $newPath = 'uploads/' . trim($targetDirectory, '/') . '/' . $filename . ($extension ? '.' . $extension : '');

        Storage::disk('public')->makeDirectory('uploads/' . trim($targetDirectory, '/'));
        Storage::disk('public')->move($tempPath, $newPath);

        return asset('storage/' . $newPath);
    }

    public function finalizeContentUrls(?string $content, ?string $token, string $targetDirectory): ?string
    {
        if (!$content || !$this->isValidToken($token)) {
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

    private function extractTempPathFromUrl(string $url, string $token): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path)) {
            return null;
        }

        $needle = '/storage/' . $this->tempDirectory($token) . '/';
        if (!str_contains($path, $needle)) {
            return null;
        }

        $storagePos = strpos($path, '/storage/');
        if ($storagePos === false) {
            return null;
        }

        return ltrim(substr($path, $storagePos + strlen('/storage/')), '/');
    }
}