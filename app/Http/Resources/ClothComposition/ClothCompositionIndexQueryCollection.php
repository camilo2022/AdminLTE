<?php

namespace App\Http\Resources\ClothComposition;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClothCompositionIndexQueryCollection extends ResourceCollection
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
            'clothCompositions' => $this->collection->map(function ($clothComposition) {
                return [
                    'id' => $clothComposition->id,
                    'name' => $clothComposition->name,
                    'description' => $clothComposition->description,
                    'created_at' => $this->formatDate($clothComposition->created_at),
                    'updated_at' => $this->formatDate($clothComposition->updated_at),
                    'deleted_at' => $clothComposition->deleted_at
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
