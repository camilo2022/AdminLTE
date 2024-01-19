<?php

namespace App\Http\Resources\Product;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductIndexQueryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'products' => $this->collection->map(function ($product) {
                return [
                    'id' => $product->id,
                    'code' => $product->code,
                    'cost' => $product->cost,
                    'price' => $product->price,
                    'clothing_line' =>$product->clothing_line,
                    'category' => $product->category,
                    'subcategory' =>$product->subcategory,
                    'model' => $product->model,
                    'trademark' => $product->trademark,
                    'correria' => $product->correria,
                    'colors_tones' => $product->colors_tones->map(function ($color_tone) {
                            return [
                                'color' => $color_tone->color,
                                'tone' => $color_tone->tone
                            ];
                        }
                    )->toArray(),
                    'sizes' => $product->sizes,
                    'created_at' => $this->formatDate($product->created_at),
                    'updated_at' => $this->formatDate($product->updated_at),
                    'deleted_at' => $product->deleted_at
                ];
            }),

            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
