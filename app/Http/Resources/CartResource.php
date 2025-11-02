<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'total' => $this->total,
            'status' => $this->status,
            'session_id' => $this->session_id ?? null,
            'coupon_id' => $this->coupon_id ?? null,
            'discount_value' => $this->discount_value ?? 0,
            'user' => $this->user_id,
            'items' => CartItemResource::collection($this->whenLoaded("items")) 
        ];
    }
}
