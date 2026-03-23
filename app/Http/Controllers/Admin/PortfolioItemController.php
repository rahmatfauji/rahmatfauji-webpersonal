<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortfolioItemRequest;
use App\Http\Requests\UpdatePortfolioItemRequest;
use App\Models\PortfolioItem;
use App\Services\TempUploadService;
use Throwable;

class PortfolioItemController extends Controller
{
    public function __construct(private TempUploadService $tempUploadService)
    {
    }

    public function index()
    {
        return view('admin.portfolio-items.index', [
            'items' => PortfolioItem::query()->orderBy('display_order')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.portfolio-items.create');
    }

    public function store(StorePortfolioItemRequest $request)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['image_url'] = $this->tempUploadService->finalizeUrl(
            $validated['image_url'] ?? null,
            $uploadToken,
            'portfolio'
        );
        $validated['is_active'] = $request->boolean('is_active');

        try {
            PortfolioItem::query()->create($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.portfolio-items.index')->with('status', __('Portfolio item added successfully.'));
    }

    public function edit(PortfolioItem $portfolioItem)
    {
        return view('admin.portfolio-items.edit', [
            'item' => $portfolioItem,
        ]);
    }

    public function update(UpdatePortfolioItemRequest $request, PortfolioItem $portfolioItem)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['image_url'] = $this->tempUploadService->finalizeUrl(
            $validated['image_url'] ?? null,
            $uploadToken,
            'portfolio'
        );
        $validated['is_active'] = $request->boolean('is_active');

        try {
            $portfolioItem->update($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.portfolio-items.index')->with('status', __('Portfolio item updated successfully.'));
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        $portfolioItem->delete();

        return redirect()->route('admin.portfolio-items.index')->with('status', __('Portfolio item deleted successfully.'));
    }
}
