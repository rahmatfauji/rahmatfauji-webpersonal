<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use App\Services\TempUploadService;
use Throwable;

class ProfileController extends Controller
{
    public function __construct(private TempUploadService $tempUploadService)
    {
    }

    public function index()
    {
        $profile = Profile::query()->oldest()->first();

        return view('admin.profiles.index', [
            'profile' => $profile,
        ]);
    }

    public function create()
    {
        $existingProfile = Profile::query()->oldest()->first();

        if ($existingProfile) {
            return redirect()->route('admin.profiles.edit', $existingProfile)
                ->with('status', __('Only one profile is allowed. You can update the existing profile.'));
        }

        return view('admin.profiles.create');
    }

    public function store(StoreProfileRequest $request)
    {
        $existingProfile = Profile::query()->oldest()->first();
        if ($existingProfile) {
            return redirect()->route('admin.profiles.edit', $existingProfile)
                ->with('status', __('Only one profile is allowed. You can update the existing profile.'));
        }

        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['avatar_url'] = $this->tempUploadService->finalizeUrl(
            $validated['avatar_url'] ?? null,
            $uploadToken,
            'profile'
        );

        try {
            Profile::query()->create($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.profiles.index')->with('status', __('Profile added successfully.'));
    }

    public function edit(Profile $profile)
    {
        return view('admin.profiles.edit', [
            'profile' => $profile,
        ]);
    }

    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $validated = $request->validated();
        $uploadToken = $request->input('upload_token');
        unset($validated['upload_token']);

        $validated['avatar_url'] = $this->tempUploadService->finalizeUrl(
            $validated['avatar_url'] ?? null,
            $uploadToken,
            'profile'
        );

        try {
            $profile->update($validated);
        } catch (Throwable $e) {
            $this->tempUploadService->cleanupToken($uploadToken);
            throw $e;
        }

        $this->tempUploadService->cleanupToken($uploadToken);

        return redirect()->route('admin.profiles.index')->with('status', __('Profile updated successfully.'));
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return redirect()->route('admin.profiles.index')->with('status', __('Profile deleted successfully.'));
    }
}
