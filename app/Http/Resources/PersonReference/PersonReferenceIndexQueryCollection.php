<?php

namespace App\Http\Resources\PersonReference;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonReferenceIndexQueryCollection extends ResourceCollection
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
            'peopleReferences' => $this->collection->map(function ($peopleReference) {
                return [
                    'id' => $peopleReference->id,
                    'person_id' => $peopleReference->person_id,
                    'person' => $peopleReference->person,
                    'name' => $peopleReference->name,
                    'last_name' => $peopleReference->last_name,
                    'document_type_id' => $peopleReference->document_type_id,
                    'document_type' => $peopleReference->document_type,
                    'document_number' => $peopleReference->document_number,
                    'country_id' => $peopleReference->country_id,
                    'country' => $peopleReference->country->name,
                    'departament_id' => $peopleReference->departament_id,
                    'departament' => $peopleReference->departament->name,
                    'city_id' => $peopleReference->city_id,
                    'city' => $peopleReference->city->name,
                    'address' => $peopleReference->address,
                    'neighbourhood' => $peopleReference->neighbourhood,
                    'email' => $peopleReference->email,
                    'telephone_number_first' => $peopleReference->telephone_number_first,
                    'telephone_number_second' => $peopleReference->telephone_number_second,
                    'created_at' => $this->formatDate($peopleReference->created_at),
                    'updated_at' => $this->formatDate($peopleReference->updated_at),
                    'deleted_at' => $peopleReference->deleted_at
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
