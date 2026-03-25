<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortfolioItemRequest;
use App\Http\Requests\UpdatePortfolioItemRequest;
use App\Models\PortfolioItem;
use App\Services\MediaCleanupService;
use App\Services\TempUploadService;
use Illuminate\Http\Request;
use Throwable;

class PortfolioItemController extends Controller
{
    public function __construct(
        private TempUploadService $tempUploadService,
        private MediaCleanupService $mediaCleanupService
    )
    {
    }

    public function index()
    {
        $items = PortfolioItem::query()->orderBy('display_order')->paginate(10);

        session()->put('admin.bulk.portfolio_item_ids', $items->getCollection()->pluck('id')->map(fn ($id) => (int) $id)->all());

        return view('admin.portfolio-items.index', [
            'items' => $items,
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
        $this->mediaCleanupService->cleanupPortfolioItemAssets($portfolioItem);
        $portfolioItem->delete();

        return redirect()->route('admin.portfolio-items.index')->with('status', __('Portfolio item deleted successfully.'));
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:portfolio_items,id'],
        ]);

        $selectedIds = array_values(array_unique(array_map('intval', $validated['ids'])));
        $allowedIds = collect(session('admin.bulk.portfolio_item_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $forbiddenIds = array_diff($selectedIds, $allowedIds);
        if (!empty($forbiddenIds)) {
            return redirect()
                ->route('admin.portfolio-items.index')
                ->withErrors(['bulk_delete' => __('Invalid selection detected. Please reload this page and try again.')]);
        }

        $items = PortfolioItem::query()
            ->whereIn('id', $selectedIds)
            ->get();

        foreach ($items as $item) {
            $this->mediaCleanupService->cleanupPortfolioItemAssets($item);
        }

        $deletedCount = PortfolioItem::query()
            ->whereIn('id', $selectedIds)
            ->delete();

        return redirect()
            ->route('admin.portfolio-items.index')
            ->with('status', trans_choice(':count portfolio item deleted successfully.|:count portfolio items deleted successfully.', $deletedCount, ['count' => $deletedCount]));
    }
}
