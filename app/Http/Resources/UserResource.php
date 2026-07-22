<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private function fixUrl(string $url, Request $request): string
    {
        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $base = $request->getSchemeAndHttpHost();
            return preg_replace('#https?://(localhost|127\.0\.0\.1)(:\d+)?/storage#', $base . '/storage', $url);
        }
        return $url;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->whenLoaded('images', function () use ($request) {
                $url = $this->images->first()->url ?? null;
                return $url ? $this->fixUrl($url, $request) : null;
            }),
            'images' => $this->whenLoaded('images', function () use ($request) {
                return $this->images->map(fn($img) => [
                    'id' => $img->id,
                    'url' => $this->fixUrl($img->url, $request),
                ]);
            }),
            'image_count' => $this->whenCounted('images'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
