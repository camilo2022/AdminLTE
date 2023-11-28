<?php

namespace App\Http\Resources\Size;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SizeIndexQueryCollection extends ResourceCollection
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
            'sizes' => $this->collection->map(function ($size) {
                return [
                    'id' => $size->id,
                    'name' => $size->name,
                    'code' => $size->code,
                    'created_at' => $this->formatDate($size->created_at),
                    'updated_at' => $this->formatDate($size->updated_at),
                    'deleted_at' => $size->deleted_at
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
