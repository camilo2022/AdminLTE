<?php

namespace App\Http\Resources\ReturnType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReturnTypeIndexQueryCollection extends ResourceCollection
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
            'returnTypes' => $this->collection->map(function ($returnType) {
                return [
                    'id' => $returnType->id,
                    'name' => $returnType->name,
                    'created_at' => $this->formatDate($returnType->created_at),
                    'updated_at' => $this->formatDate($returnType->updated_at),
                    'deleted_at' => $returnType->deleted_at
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
