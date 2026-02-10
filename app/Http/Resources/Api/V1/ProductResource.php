<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title, 
            'slug' => $this->slug, 
            'description' => $this->description, 
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'stock' => $this->stock,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count,
            'is_featured' => (bool) $this->is_featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'seller' => new UserResource($this->whenLoaded('seller')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
