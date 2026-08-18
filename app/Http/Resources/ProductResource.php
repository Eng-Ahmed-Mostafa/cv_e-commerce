<?php

namespace App\Http\Resources;

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
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'images' => $this->images ? collect($this->images)->map(fn($img) => asset('storage/' . $img)) : [],
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => number_format($this->price,2),
            'sale_price' => number_format($this->sale_price,2),
            'SKU' => $this->SKU,
            'feature' => $this->feature,
            'stock' => $this->stock,
            'quantity' => $this->quantity,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'date' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
