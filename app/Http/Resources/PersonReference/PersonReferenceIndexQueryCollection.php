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
            'personReferences' => $this->collection->map(function ($personReference) {
                return [
                    'id' => $personReference->id,
                    'person_id' => $personReference->person_id,
                    'person' => $personReference->person,
                    'name' => $personReference->name,
                    'last_name' => $personReference->last_name,
                    'document_type_id' => $personReference->document_type_id,
                    'document_type' => $personReference->document_type,
                    'document_number' => $personReference->document_number,
                    'country_id' => $personReference->country_id,
                    'country' => $personReference->country,
                    'departament_id' => $personReference->departament_id,
                    'departament' => $personReference->departament,
                    'city_id' => $personReference->city_id,
                    'city' => $personReference->city,
                    'address' => $personReference->address,
                    'neighborhood' => $personReference->neighborhood,
                    'email' => $personReference->email,
                    'telephone_number_first' => $personReference->telephone_number_first,
                    'telephone_number_second' => $personReference->telephone_number_second,
                    'created_at' => $this->formatDate($personReference->created_at),
                    'updated_at' => $this->formatDate($personReference->updated_at),
                    'deleted_at' => $personReference->deleted_at
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
