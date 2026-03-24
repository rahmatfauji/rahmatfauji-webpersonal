<?php

$readEnvValue = static function (string $key): ?string {
    $candidates = [$_ENV[$key] ?? null, $_SERVER[$key] ?? null, getenv($key) ?: null];

    foreach ($candidates as $candidate) {
        if (is_string($candidate) && $candidate !== '') {
            return $candidate;
        }
    }

    $envPath = base_path('.env');
    if (!is_file($envPath) || !is_readable($envPath)) {
        return null;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return null;
    }

    foreach ($lines as $line) {
        $trimmedLine = trim($line);
        if ($trimmedLine === '' || str_starts_with($trimmedLine, '#')) {
            continue;
        }

        if (!str_starts_with($trimmedLine, $key . '=')) {
            continue;
        }

        $value = substr($trimmedLine, strlen($key) + 1);
        $value = trim($value);

        if ($value === '' || strtolower($value) === 'null') {
            return null;
        }

        return trim($value, "\"'");
    }

    return null;
};

$baseParentPath = dirname(base_path());
$sharedHostingStoragePath = $baseParentPath . DIRECTORY_SEPARATOR . 'storage';
$defaultPublicStoragePath = basename($baseParentPath) === 'public_html'
    ? $sharedHostingStoragePath
    : public_path('storage');
$publicStoragePath = $readEnvValue('PUBLIC_STORAGE_PATH') ?: $defaultPublicStoragePath;
$publicStorageUrl = $readEnvValue('PUBLIC_STORAGE_URL') ?: (env('APP_URL') ?: 'http://localhost') . '/storage';

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => $publicStoragePath,
            'url' => $publicStorageUrl,
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
