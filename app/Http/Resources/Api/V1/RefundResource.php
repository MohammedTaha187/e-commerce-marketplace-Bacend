<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'order_item_id' => $this->order_item_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'amount' => $this->amount,
            'reason' => $this->reason,
            'status' => $this->status,
            'images' => $this->images ? array_map(fn($img) => url('storage/' . $img), $this->images) : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
