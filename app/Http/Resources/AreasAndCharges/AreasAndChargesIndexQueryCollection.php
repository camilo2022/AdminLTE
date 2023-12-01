<?php

namespace App\Http\Resources\AreasAndCharges;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AreasAndChargesIndexQueryCollection extends ResourceCollection
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
            'areas' => $this->collection->map(function ($area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                    'description' => $area->description,
                    'charges' => $area->charges->map(function ($charges) {
                        return [
                            'id' => $charges->id,
                            'name' => $charges->name,
                            'description' => $charges->description,
                            'created_at' => $this->formatDate($charges->created_at),
                            'updated_at' => $this->formatDate($charges->updated_at),
                            'deleted_at' => $charges->deleted_at
                        ];
                    })->toArray(),
                    'created_at' => $this->formatDate($area->created_at),
                    'updated_at' => $this->formatDate($area->updated_at),
                    'deleted_at' => $area->deleted_at
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
