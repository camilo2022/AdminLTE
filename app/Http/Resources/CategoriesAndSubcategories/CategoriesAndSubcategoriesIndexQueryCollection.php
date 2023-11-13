<?php

namespace App\Http\Resources\CategoriesAndSubcategories;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoriesAndSubcategoriesIndexQueryCollection extends ResourceCollection
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
            'categories' => $this->collection->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'code' => $category->code,
                    'description' => $category->description,
                    'clothingLine' => (object) [
                        'id' => $category->clothing_line->id,
                        'name' => $category->clothing_line->name,
                        'code' => $category->clothing_line->code,
                        'description' => $category->clothing_line->description,
                        'created_at' => Carbon::parse($category->clothing_line->created_at)->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::parse($category->clothing_line->updated_at)->format('Y-m-d H:i:s'),
                        'deleted_at' => Carbon::parse($category->clothing_line->deleted_at)->format('Y-m-d H:i:s')
                    ],
                    'subcategories' => $category->subcategories->map(function ($subcategory) {
                            return [
                                'id' => $subcategory->id,
                                'name' => $subcategory->name,
                                'code' => $subcategory->code,
                                'description' => $subcategory->description,
                                'created_at' => Carbon::parse($subcategory->created_at)->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse($subcategory->updated_at)->format('Y-m-d H:i:s'),
                                'deleted_at' => $subcategory->deleted_at
                            ];
                        }
                    )->toArray(),
                    'created_at' => Carbon::parse($category->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($category->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $category->deleted_at
                ];
            }),
            
            'meta' => [
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
            ],
        ];
    }
}
