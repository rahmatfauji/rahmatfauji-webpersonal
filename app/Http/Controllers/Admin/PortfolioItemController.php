<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortfolioItemRequest;
use App\Http\Requests\UpdatePortfolioItemRequest;
use App\Models\PortfolioItem;

class PortfolioItemController extends Controller
{
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
        $validated['is_active'] = $request->boolean('is_active');
        PortfolioItem::query()->create($validated);

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
        $validated['is_active'] = $request->boolean('is_active');
        $portfolioItem->update($validated);

        return redirect()->route('admin.portfolio-items.index')->with('status', __('Portfolio item updated successfully.'));
    }

    public function destroy(PortfolioItem $portfolioItem)
    {
        $portfolioItem->delete();

        return redirect()->route('admin.portfolio-items.index')->with('status', __('Portfolio item deleted successfully.'));
    }
}
