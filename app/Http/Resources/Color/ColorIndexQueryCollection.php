<?php

namespace App\Http\Resources\Color;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ColorIndexQueryCollection extends ResourceCollection
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
            'colors' => $this->collection->map(function ($color) {
                return [
                    'id' => $color->id,
                    'name' => $color->name,
                    'code' => $color->code,
                    'value' => $color->value,
                    'created_at' => Carbon::parse($color->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($color->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $color->deleted_at
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
