<?php
  
namespace App\Http\Resources;
  
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
  
class ProductResource extends JsonResource
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
            'composition' => $this->composition,
            'pmh_reference_code' => $this->pmh_reference_code,
            'article_code' => $this->article_code,
            'price'=>$this->price,
            'is_active'=>$this->is_active,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'subcategory' => $this->subcategory ? [
                'id' => $this->subcategory->id,
                'name' => $this->subcategory->name,
            ] : null,
            'photos' => $this->photoProducts ? $this->photoProducts->map(function ($photo) {
                return str_contains($photo->url, 'https') ? $photo->url : asset('storage/' . $photo->url);
            }) : [],
            'brand_id'=>$this->brand_id? [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
            ] : null,
            'material_id'=>$this->material_id? [
                'id' => $this->material->id,
                'name' => $this->material->name,
            ] : null,
            'status_product_id'=>$this->status_product_id? [
                'id' => $this->status_product->id,
                'name' => $this->status_product->name,
            ] : null,
            'size_ids'=>$this->sizes($this->size_ids),
            'color_id'=>$this->color_id? [
                'id' => $this->color->id,
                'name' => $this->color->name,
            ] : null,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
