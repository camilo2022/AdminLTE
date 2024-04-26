<?php

namespace App\Http\Resources\ClothType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClothTypeIndexQueryCollection extends ResourceCollection
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
            'clothTypes' => $this->collection->map(function ($clothType) {
                return [
                    'id' => $clothType->id,
                    'name' => $clothType->name,
                    'code' => $clothType->code,
                    'description' => $clothType->description,
                    'created_at' => $this->formatDate($clothType->created_at),
                    'updated_at' => $this->formatDate($clothType->updated_at),
                    'deleted_at' => $clothType->deleted_at
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
