<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayoutResource extends JsonResource
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
            'seller' => new UserResource($this->whenLoaded('seller')),
            'amount' => $this->amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'details' => $this->details, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
