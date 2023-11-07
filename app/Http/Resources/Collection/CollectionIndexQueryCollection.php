<?php

namespace App\Http\Resources\Collection;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectionIndexQueryCollection extends ResourceCollection
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
            'collections' => $this->collection->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'name' => $collection->name,
                    'code' => $collection->code,
                    'start_date' => Carbon::parse($collection->start_date)->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::parse($collection->end_date)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($collection->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($collection->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $collection->deleted_at
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
