<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return User::with('image')->get()->map(fn($user) => new UserResource($user));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $user->image()->create(['url' => Storage::url($path)]);
        }

        return response()->json(
            new UserResource($user->load('image')),
            201
        );
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(
            new UserResource($user->load('image')),
            200
        );
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->update($validated);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->image->url));
                $user->image->delete();
            }
            $path = $request->file('image')->store('images', 'public');
            $user->image()->create(['url' => Storage::url($path)]);
        }

        return response()->json(
            new UserResource($user->load('image')),
            200
        );
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->image->url));
            $user->image->delete();
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
