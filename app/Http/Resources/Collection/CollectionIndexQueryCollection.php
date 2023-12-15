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
                    'created_at' => $this->formatDate($collection->created_at),
                    'updated_at' => $this->formatDate($collection->updated_at),
                    'deleted_at' => $collection->deleted_at
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
