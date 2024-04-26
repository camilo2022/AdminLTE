<?php

namespace App\Http\Resources\SupplyType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplyTypeIndexQueryCollection extends ResourceCollection
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
            'supplyTypes' => $this->collection->map(function ($supplyType) {
                return [
                    'id' => $supplyType->id,
                    'name' => $supplyType->name,
                    'code' => $supplyType->code,
                    'is_cloth' => $supplyType->is_cloth,
                    'created_at' => $this->formatDate($supplyType->created_at),
                    'updated_at' => $this->formatDate($supplyType->updated_at),
                    'deleted_at' => $supplyType->deleted_at
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
