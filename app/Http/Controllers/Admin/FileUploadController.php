<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'type' => ['nullable', 'in:blog,portfolio,profile,slide'],
            'temp_token' => ['nullable', 'string', 'regex:/^[A-Za-z0-9_-]{10,100}$/'],
        ]);

        $file = $validated['image'];
        $type = $validated['type'] ?? 'general';
        $tempToken = $validated['temp_token'] ?? null;
        $directory = $tempToken ? "uploads/tmp/{$tempToken}" : "uploads/{$type}";

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $extension = $file->guessExtension() ?? 'bin';
        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs($directory, $filename, 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path),
            'path' => $path,
            'temporary' => (bool) $tempToken,
        ]);
    }
}
