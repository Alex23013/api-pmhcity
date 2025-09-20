<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'lastname'         => $this->lastname,
            'phone'            => $this->phone,
            'email'            => $this->email,
            'store_name'       => $this->store_name,
            'store_location'   => $this->store_location,
            'store_url'        => $this->store_url,
            'store_description'=> $this->store_description,
            'admin_notes'      => $this->admin_notes,
            'category'         => $this->category,
            'status'           => $this->status,
            'created_at'       => $this->created_at?->toDateTimeString(),
            'updated_at'       => $this->updated_at?->toDateTimeString(),
        ];
    }
}
