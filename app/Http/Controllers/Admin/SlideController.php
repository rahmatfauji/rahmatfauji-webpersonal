<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Slide;
use App\Services\TempUploadService;
use Throwable;

class SlideController extends Controller
{
    public function __construct(private TempUploadService $tempUploadService)
    {
    }

    public function index()
    {
        return view('admin.slides.index', [
            'slides' => Slide::query()->orderBy('display_order')->paginate(12),
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
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('status', __('Slide deleted successfully.'));
    }
}
