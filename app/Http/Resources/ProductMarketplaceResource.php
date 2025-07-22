<?php
  
namespace App\Http\Resources;
  
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
  
class ProductMarketplaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pmh_reference_code' => $this->pmh_reference_code,
            'article_code' => $this->article_code,
            'photos' => $this->photoProducts ? $this->photoProducts->map(function ($photo) {
                return str_contains($photo->url, 'https') ? $photo->url : asset('storage/' . $photo->url);
            }) : [],
            'price' => $this->price,
            'is_active' => $this->is_active,
        ];
    }
}
