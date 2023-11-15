<?php

namespace App\Http\Resources\Warehose;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseIndexQueryCollection extends ResourceCollection
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
            'warehouses' => $this->collection->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'code' => $warehouse->code,
                    'description' => $warehouse->description,
                    'created_at' => Carbon::parse($warehouse->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($warehouse->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $warehouse->deleted_at
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
