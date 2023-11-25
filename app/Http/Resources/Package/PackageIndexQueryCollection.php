<?php

namespace App\Http\Resources\Package;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PackageIndexQueryCollection extends ResourceCollection
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
            'packages' => $this->collection->map(function ($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'code' => $package->code,
                    'created_at' => $this->formatDate($package->created_at),
                    'updated_at' => $this->formatDate($package->updated_at),
                    'deleted_at' => $package->deleted_at
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
