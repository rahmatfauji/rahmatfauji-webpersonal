<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Slide;

class SlideController extends Controller
{
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
        $validated['is_active'] = $request->boolean('is_active');

        Slide::query()->create($validated);

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
        $validated['is_active'] = $request->boolean('is_active');

        $slide->update($validated);

        return redirect()->route('admin.slides.index')->with('status', __('Slide updated successfully.'));
    }

    public function destroy(Slide $slide)
    {
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('status', __('Slide deleted successfully.'));
    }
}
