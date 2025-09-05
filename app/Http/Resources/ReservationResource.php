<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'price' => round($this->price,2),
            'size_id' => $this->size_id,
            'color_id' => $this->color_id,
            'buyer_id' => $this->buyer_id,
            'seller_id' => $this->seller_id,
            'product_id' => $this->product_id,
            'buyer' => $this->buyer,
            'seller' => $this->seller,
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'last_status' => $this->last_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
