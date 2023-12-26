<?php

namespace App\Http\Resources\PersonType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonTypeIndexQueryCollection extends ResourceCollection
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
            'personTypes' => $this->collection->map(function ($personType) {
                return [
                    'id' => $personType->id,
                    'name' => $personType->name,
                    'code' => $personType->code,
                    'require_references' => $personType->require_references,
                    'created_at' => $this->formatDate($personType->created_at),
                    'updated_at' => $this->formatDate($personType->updated_at),
                    'deleted_at' => $personType->deleted_at
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
