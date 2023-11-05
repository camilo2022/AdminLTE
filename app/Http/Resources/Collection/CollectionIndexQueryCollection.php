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
            'collections' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'code' => $user->code,
                    'start_date' => Carbon::parse($user->start_date)->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::parse($user->end_date)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($user->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($user->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $user->deleted_at
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
