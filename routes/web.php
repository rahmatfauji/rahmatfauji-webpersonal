<?php

use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\FileUploadController;
use App\Http\Controllers\Admin\PortfolioItemController as AdminPortfolioItemController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SlideController as AdminSlideController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\BlogPost;
use App\Models\PortfolioItem;
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
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::post('/upload-image', [FileUploadController::class, 'uploadImage'])->name('upload-image')->middleware('throttle:upload');
    Route::resource('profiles', AdminProfileController::class)->except(['show']);
    Route::resource('slides', AdminSlideController::class)->except(['show']);
    Route::resource('blog-posts', AdminBlogPostController::class)->except(['show']);
    Route::resource('portfolio-items', AdminPortfolioItemController::class)->except(['show']);
});
