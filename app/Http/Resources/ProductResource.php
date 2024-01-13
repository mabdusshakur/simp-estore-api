<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name ?? '',
            'slug' => $this->slug ?? '',
            'description' => $this->description ?? '',
            'regular_price' => $this->regular_price ?? '',
            'sale_price' => $this->sale_price ?? '',
            'category_id' => $this->category_id ?? '',
            'subcategory_id' => $this->subcategory_id ?? '',
            'status' => $this->status ?? '',
            'stock' => $this->stock ?? '',
            'view_count' => $this->view_count ?? '',
            'sold_count' => $this->sold_count ?? '',
            'images' => ImageResource::collection($this->images),
            'category' => new CategoryResource($this->category),
            'subcategory' => new SubCategoryResource($this->subcategory),
        ];
    }
}
