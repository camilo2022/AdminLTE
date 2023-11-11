<?php

namespace App\Http\Resources\ClothingLine;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClothingLineIndexQueryCollection extends ResourceCollection
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
            'clothingLines' => $this->collection->map(function ($clothingLine) {
                return [
                    'id' => $clothingLine->id,
                    'name' => $clothingLine->name,
                    'code' => $clothingLine->code,
                    'description' => $clothingLine->description,
                    'created_at' => Carbon::parse($clothingLine->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($clothingLine->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $clothingLine->deleted_at
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
