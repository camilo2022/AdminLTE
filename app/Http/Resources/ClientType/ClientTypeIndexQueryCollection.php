<?php

namespace App\Http\Resources\ClientType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientTypeIndexQueryCollection extends ResourceCollection
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
            'clientTypes' => $this->collection->map(function ($clientType) {
                return [
                    'id' => $clientType->id,
                    'name' => $clientType->name,
                    'code' => $clientType->code,
                    'created_at' => $this->formatDate($clientType->created_at),
                    'updated_at' => $this->formatDate($clientType->updated_at),
                    'deleted_at' => $clientType->deleted_at
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
