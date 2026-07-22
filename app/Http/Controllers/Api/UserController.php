<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return User::with('images')->get()->map(fn($user) => new UserResource($user));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'required|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $user->images()->create(['url' => Storage::disk('public')->url($path)]);
            }
        }

        return response()->json(
            new UserResource($user->load('images')),
            201
        );
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(
            new UserResource($user->load('images')),
            200
        );
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                $user->images()->create(['url' => Storage::disk('public')->url($path)]);
            }
        }

        return response()->json(
            new UserResource($user->load('images')),
            200
        );
    }

    public function destroy(User $user): JsonResponse
    {
        foreach ($user->images as $image) {
            $path = str_replace('/storage/', '', $image->url);
            Storage::disk('public')->delete($path);
            $image->delete();
        }

        $user->delete();

        return response()->json(null, 204);
    }

    public function addImages(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('images', 'public');
            $user->images()->create(['url' => Storage::disk('public')->url($path)]);
        }

        return response()->json(
            new UserResource($user->load('images')),
            200
        );
    }

    public function deleteImage(Image $image): JsonResponse
    {
        $path = str_replace('/storage/', '', $image->url);
        Storage::disk('public')->delete($path);
        $image->delete();

        return response()->json(null, 204);
    }
}
