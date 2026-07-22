<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->whenLoaded('images', function () {
                return $this->images->first()->url ?? null;
            }),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn($img) => [
                    'id' => $img->id,
                    'url' => $img->url,
                ]);
            }),
            'image_count' => $this->whenCounted('images'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
