<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'discount_code' => $this->code,
            'discount_type' => $this->type,
            'discount_value' => $this->value,
            'cart_value' => $this->cart_value,
            'expiry_date' => $this->expiry_date ? Carbon::parse($this->expiry_date)->format('Y-m-d') : null,
            'date' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
