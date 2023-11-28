<?php

namespace App\Http\Resources\PackageType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PackageTypeIndexQueryCollection extends ResourceCollection
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
            'packageTypes' => $this->collection->map(function ($packageType) {
                return [
                    'id' => $packageType->id,
                    'name' => $packageType->name,
                    'code' => $packageType->code,
                    'created_at' => $this->formatDate($packageType->created_at),
                    'updated_at' => $this->formatDate($packageType->updated_at),
                    'deleted_at' => $packageType->deleted_at
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
