<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profiles.index', [
            'profiles' => Profile::query()->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.profiles.create');
    }

    public function store(StoreProfileRequest $request)
    {
        Profile::query()->create($request->validated());

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
        $profile->update($request->validated());

        return redirect()->route('admin.profiles.index')->with('status', __('Profile updated successfully.'));
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return redirect()->route('admin.profiles.index')->with('status', __('Profile deleted successfully.'));
    }
}
