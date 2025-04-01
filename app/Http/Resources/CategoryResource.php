<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cover_image' => $this->cover_image ? (str_contains($this->cover_image, 'https') ? $this->cover_image : asset('storage/' . $this->cover_image)) : null,
            'subcategories' => $this->subcategories->map(function ($subcategory) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'cover_image' => $subcategory->cover_image ? (str_contains($subcategory->cover_image, 'https') ? $subcategory->cover_image : asset('storage/' . $subcategory->cover_image)) : null,
                ];
            }),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
