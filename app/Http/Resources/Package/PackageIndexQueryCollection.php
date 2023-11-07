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
                    'created_at' => Carbon::parse($package->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($package->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $package->deleted_at
                ];
            }),
            'meta' => [
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
            ],
        ];
    }
}
