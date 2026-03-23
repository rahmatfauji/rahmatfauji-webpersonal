<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\BlogPostRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Services\BlogPostService;
use App\Services\TempUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class BlogPostController extends Controller
{
    public function __construct(
        private BlogPostRepositoryInterface $blogPostRepository,
        private BlogPostService $blogPostService,
        private TempUploadService $tempUploadService
    ) {
    }

    public function index()
    {
        return view('admin.blog-posts.index', [
            'posts' => $this->blogPostRepository->paginate(10),
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
        $this->blogPostRepository->delete($blogPost);

        return redirect()->route('admin.blog-posts.index')->with('status', __('Article deleted successfully.'));
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $image = $validated['image'];
        $directory = public_path('uploads/blog-inline');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Use MIME-detected extension — never trust the client-supplied filename extension
        $extension = $image->guessExtension() ?? 'bin';
        $filename = uniqid('blog_', true) . '.' . $extension;
        $image->move($directory, $filename);

        return response()->json([
            'location' => asset('uploads/blog-inline/' . $filename),
            'url' => asset('uploads/blog-inline/' . $filename),
        ]);
    }
}
