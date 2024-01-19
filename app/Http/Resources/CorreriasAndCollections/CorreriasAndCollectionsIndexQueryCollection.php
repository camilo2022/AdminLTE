<?php

namespace App\Http\Resources\CorreriasAndCollections;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CorreriasAndCollectionsIndexQueryCollection extends ResourceCollection
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
            'correriasAndCollections' => $this->collection->map(function ($correriasAndCollection) {
                return [
                    'id' => $correriasAndCollection->id,
                    'name' => $correriasAndCollection->name,
                    'code' => $correriasAndCollection->code,
                    'start_date' => $this->formatDate($correriasAndCollection->start_date),
                    'end_date' => $this->formatDate($correriasAndCollection->end_date),
                    'collection' => $correriasAndCollection->collection,
                    'created_at' => $this->formatDate($correriasAndCollection->created_at),
                    'updated_at' => $this->formatDate($correriasAndCollection->updated_at),
                    'deleted_at' => $correriasAndCollection->deleted_at
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
