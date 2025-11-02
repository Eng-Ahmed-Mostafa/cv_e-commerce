<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'discount' => number_format($this->discount,2),
            'tax' => number_format($this->tax,2),
            'total' => number_format($this->total,2),
            'status' => $this->status,
            'total_amount' => number_format($this->total_amount,2),
            'ordered_date' => $this->ordered_date?->format('Y-m-d'),
            'delivered_date' => $this->delivered_date?->format('Y-m-d'),
            'order_items' => OrderItmesResource::collection($this->whenLoaded('order_items')),
            'user_details' => new DetailsResource($this->whenLoaded('detail')),
            'user_id' => $this->user_id
        ];
    }
}
