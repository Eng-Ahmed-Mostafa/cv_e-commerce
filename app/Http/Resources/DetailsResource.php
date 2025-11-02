<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'pincode' => $this->pincode,
            'state' => $this->state,
            'town' => $this->town,
            'city' => $this->city,
            'no_building' => $this->no_building,
            'area' => $this->area,
            'landmark' => $this->landmark,
        ];
    }
}
