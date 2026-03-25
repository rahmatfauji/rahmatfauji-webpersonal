<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Slide;
use App\Services\MediaCleanupService;
use App\Services\TempUploadService;
use Illuminate\Http\Request;
use Throwable;

class SlideController extends Controller
{
    public function __construct(
        private TempUploadService $tempUploadService,
        private MediaCleanupService $mediaCleanupService
    )
    {
    }

    public function index()
    {
        $slides = Slide::query()->orderBy('display_order')->paginate(12);

        session()->put('admin.bulk.slide_ids', $slides->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->all());

        return view('admin.slides.index', [
            'slides' => $slides,
        ]);
    }

    public function create()
    {
        return view('admin.slides.create');
    }

    public function store(StoreSlideRequest $request)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['image_url'] = $this->tempUploadService->finalizeUrl(
            $validated['image_url'] ?? null,
            $uploadToken,
            'slide'
        );
        $validated['is_active'] = $request->boolean('is_active');

        try {
            Slide::query()->create($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.slides.index')->with('status', __('Slide added successfully.'));
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.edit', [
            'slide' => $slide,
        ]);
    }

    public function update(UpdateSlideRequest $request, Slide $slide)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['image_url'] = $this->tempUploadService->finalizeUrl(
            $validated['image_url'] ?? null,
            $uploadToken,
            'slide'
        );

        if (blank($validated['image_url'])) {
            $validated['image_url'] = $slide->image_url;
        }

        $validated['is_active'] = $request->boolean('is_active');

        try {
            $slide->update($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.slides.index')->with('status', __('Slide updated successfully.'));
    }

    public function destroy(Slide $slide)
    {
        $this->mediaCleanupService->cleanupSlideAssets($slide);
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('status', __('Slide deleted successfully.'));
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:slides,id'],
        ]);

        $selectedIds = array_values(array_unique(array_map('intval', $validated['ids'])));
        $allowedIds = collect(session('admin.bulk.slide_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $forbiddenIds = array_diff($selectedIds, $allowedIds);
        if (!empty($forbiddenIds)) {
            return redirect()
                ->route('admin.slides.index')
                ->withErrors(['bulk_delete' => __('Invalid selection detected. Please reload this page and try again.')]);
        }

        $slides = Slide::query()
            ->whereIn('id', $selectedIds)
            ->get();

        foreach ($slides as $slide) {
            $this->mediaCleanupService->cleanupSlideAssets($slide);
        }

        $deletedCount = Slide::query()
            ->whereIn('id', $selectedIds)
            ->delete();

        return redirect()
            ->route('admin.slides.index')
            ->with('status', trans_choice(':count slide deleted successfully.|:count slides deleted successfully.', $deletedCount, ['count' => $deletedCount]));
    }
}
