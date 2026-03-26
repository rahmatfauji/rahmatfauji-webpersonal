<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\BlogPostRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Services\BlogPostService;
use App\Services\MediaCleanupService;
use App\Services\TempUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class BlogPostController extends Controller
{
    public function __construct(
        private BlogPostRepositoryInterface $blogPostRepository,
        private BlogPostService $blogPostService,
        private TempUploadService $tempUploadService,
        private MediaCleanupService $mediaCleanupService
    ) {
    }

    public function index()
    {
        $posts = $this->blogPostRepository->paginate(10);

        $tagCounts = BlogPost::query()
            ->whereNotNull('tags')
            ->get(['tags'])
            ->pluck('tags')
            ->filter(fn ($tags) => is_array($tags))
            ->flatten()
            ->filter(fn ($tag) => is_string($tag) && trim($tag) !== '')
            ->map(fn (string $tag) => trim($tag))
            ->countBy()
            ->sortDesc()
            ->take(6);

        $categoryCounts = BlogPost::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as aggregate')
            ->groupBy('category')
            ->orderByDesc('aggregate')
            ->limit(6)
            ->get();

        session()->put('admin.bulk.blog_post_ids', $posts->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->all());

        return view('admin.blog-posts.index', [
            'posts' => $posts,
            'analytics' => [
                'total_views' => (int) BlogPost::query()->sum('view_count'),
                'published_count' => (int) BlogPost::query()->where('is_published', true)->count(),
                'draft_count' => (int) BlogPost::query()->where('is_published', false)->count(),
                'average_views' => (int) round((float) BlogPost::query()->avg('view_count')),
                'top_posts' => BlogPost::query()->orderByDesc('view_count')->latest('published_at')->take(5)->get(),
                'category_counts' => $categoryCounts,
                'tag_counts' => $tagCounts,
            ],
        ]);
    }

    public function create()
    {
        return view('admin.blog-posts.create');
    }

    public function store(StoreBlogPostRequest $request)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['content'] = $this->tempUploadService->finalizeContentUrls(
            $validated['content'] ?? '',
            $uploadToken,
            'blog-inline'
        );

        $validated['featured_image'] = $this->tempUploadService->finalizeUrl(
            $validated['featured_image'] ?? null,
            $uploadToken,
            'blog-featured'
        );

        try {
            $this->blogPostService->create($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.blog-posts.index')->with('status', __('Article added successfully.'));
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts.edit', [
            'post' => $blogPost,
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['content'] = $this->tempUploadService->finalizeContentUrls(
            $validated['content'] ?? '',
            $uploadToken,
            'blog-inline'
        );

        $validated['featured_image'] = $this->tempUploadService->finalizeUrl(
            $validated['featured_image'] ?? null,
            $uploadToken,
            'blog-featured'
        );

        try {
            $this->blogPostService->update($blogPost, $validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.blog-posts.index')->with('status', __('Article updated successfully.'));
    }

    public function destroy(BlogPost $blogPost)
    {
        $this->mediaCleanupService->cleanupBlogPostAssets($blogPost);
        $this->blogPostRepository->delete($blogPost);

        return redirect()->route('admin.blog-posts.index')->with('status', __('Article deleted successfully.'));
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:blog_posts,id'],
        ]);

        $posts = BlogPost::query()
            ->whereIn('id', array_unique($validated['ids']))
            ->get();

        $requestedIds = $posts->pluck('id')->map(fn ($id) => (int) $id)->all();
        $allowedIds = collect(session('admin.bulk.blog_post_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $forbiddenIds = array_diff($requestedIds, $allowedIds);
        if (!empty($forbiddenIds)) {
            return redirect()
                ->route('admin.blog-posts.index')
                ->withErrors(['bulk_delete' => __('Invalid selection detected. Please reload this page and try again.')]);
        }

        $deletedCount = 0;

        foreach ($posts as $post) {
            $this->mediaCleanupService->cleanupBlogPostAssets($post);

            if ($this->blogPostRepository->delete($post)) {
                $deletedCount++;
            }
        }

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', trans_choice(':count article deleted successfully.|:count articles deleted successfully.', $deletedCount, ['count' => $deletedCount]));
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $image = $validated['image'];
        $directory = 'uploads/blog-inline';

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Use MIME-detected extension; never trust the client-supplied filename extension.
        $extension = $image->guessExtension() ?? 'bin';
        $filename = (string) Str::uuid() . '.' . $extension;
        $path = $image->storeAs($directory, $filename, 'public');

        return response()->json([
            'location' => Storage::disk('public')->url($path),
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
