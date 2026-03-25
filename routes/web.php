<?php

use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\FileUploadController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Admin\PortfolioItemController as AdminPortfolioItemController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SlideController as AdminSlideController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\BlogPost;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('track.activity')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/sitemap.xml', function () {
    $staticPages = collect([
        ['loc' => route('home'), 'lastmod' => now()->toDateString()],
        ['loc' => route('profile'), 'lastmod' => now()->toDateString()],
        ['loc' => route('blog.index'), 'lastmod' => now()->toDateString()],
        ['loc' => route('portfolio.index'), 'lastmod' => now()->toDateString()],
    ]);

    $blogPages = BlogPost::query()
        ->where('is_published', true)
        ->get(['slug', 'updated_at'])
        ->map(fn (BlogPost $post) => [
            'loc' => route('blog.show', $post),
            'lastmod' => optional($post->updated_at)->toDateString() ?: now()->toDateString(),
        ]);

    $portfolioUpdatedAt = PortfolioItem::query()->max('updated_at');
    $portfolioLastmod = $portfolioUpdatedAt ? date('Y-m-d', strtotime((string) $portfolioUpdatedAt)) : now()->toDateString();

    $portfolioPages = PortfolioItem::query()
        ->where('is_active', true)
        ->pluck('id')
        ->map(fn () => [
            'loc' => route('portfolio.index'),
            'lastmod' => $portfolioLastmod,
        ]);

    $urls = $staticPages
        ->merge($blogPages)
        ->merge($portfolioPages)
        ->unique('loc')
        ->values();

    $xmlBody = $urls->map(function (array $url) {
        $loc = htmlspecialchars($url['loc'], ENT_QUOTES, 'UTF-8');
        $lastmod = htmlspecialchars($url['lastmod'], ENT_QUOTES, 'UTF-8');

        return "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lastmod}</lastmod>\n    </url>";
    })->implode("\n");

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
        "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n" .
        $xmlBody . "\n" .
        "</urlset>";

    return response($xml, 200)->header('Content-Type', 'application/xml');
    })->name('sitemap');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('/blog', [HomeController::class, 'blog'])->name('blog.index');
    Route::get('/blog/{blogPost}', [HomeController::class, 'blogShow'])->name('blog.show');
    Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio.index');
    Route::get('/portfolio/{portfolioItem}/visit', [HomeController::class, 'portfolioVisit'])->name('portfolio.visit');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.attempt')
        ->middleware('throttle:login');
});

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [HomeController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/system/storage-diagnostic', function () {
        $readEnvFileValue = static function (string $key): ?string {
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

                $value = trim(substr($trimmedLine, strlen($key) + 1));

                if ($value === '' || strtolower($value) === 'null') {
                    return null;
                }

                return trim($value, "\"'");
            }

            return null;
        };

        $marker = '__storage_diagnostic_marker__';
        $markerUrl = Storage::disk('public')->url($marker);

        return response()->json([
            'app_base_path' => base_path(),
            'public_path' => public_path(),
            'env_PUBLIC_STORAGE_PATH' => env('PUBLIC_STORAGE_PATH'),
            'env_PUBLIC_STORAGE_URL' => env('PUBLIC_STORAGE_URL'),
            'raw_env_PUBLIC_STORAGE_PATH' => $readEnvFileValue('PUBLIC_STORAGE_PATH'),
            'raw_env_PUBLIC_STORAGE_URL' => $readEnvFileValue('PUBLIC_STORAGE_URL'),
            'config_filesystem_default' => config('filesystems.default'),
            'config_public_root' => config('filesystems.disks.public.root'),
            'config_public_url' => config('filesystems.disks.public.url'),
            'disk_public_url_example' => $markerUrl,
            'disk_public_root_exists' => is_dir((string) config('filesystems.disks.public.root')),
            'env_loaded_file' => file_exists(base_path('.env')) ? base_path('.env') : 'not-found',
            'opcache_enabled' => function_exists('opcache_get_status') ? (bool) (opcache_get_status(false)['opcache_enabled'] ?? false) : false,
        ]);
    })->name('system.storage-diagnostic');

    Route::get('/system/clear-runtime-cache', function () {
        Artisan::call('optimize:clear');
        $opcacheReset = function_exists('opcache_reset') ? @opcache_reset() : null;

        return response()->json([
            'ok' => true,
            'message' => trim((string) Artisan::output()) ?: 'Runtime cache cleared.',
            'opcache_reset' => $opcacheReset,
        ]);
    })->name('system.clear-runtime-cache');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('/account/password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/account/password', [AdminPasswordController::class, 'update'])->name('password.update');
    Route::post('/upload-image', [FileUploadController::class, 'uploadImage'])->name('upload-image')->middleware('throttle:upload');
    Route::resource('profiles', AdminProfileController::class)->except(['show']);
    Route::resource('slides', AdminSlideController::class)->except(['show']);
    Route::delete('/blog-posts/bulk-delete', [AdminBlogPostController::class, 'bulkDestroy'])->name('blog-posts.bulk-destroy');
    Route::resource('blog-posts', AdminBlogPostController::class)->except(['show']);
    Route::delete('/portfolio-items/bulk-delete', [AdminPortfolioItemController::class, 'bulkDestroy'])->name('portfolio-items.bulk-destroy');
    Route::resource('portfolio-items', AdminPortfolioItemController::class)->except(['show']);
});
