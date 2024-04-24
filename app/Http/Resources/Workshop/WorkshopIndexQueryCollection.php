<?php

namespace App\Http\Resources\Workshop;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkshopIndexQueryCollection extends ResourceCollection
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
            'workshops' => $this->collection->map(function ($workshop) {
                return [
                    'id' => $workshop->id,
                    'person' => $workshop->person,
                    'name' => $workshop->name,
                    'person_type_id' => $workshop->person_type_id,
                    'person_type' => $workshop->person_type,
                    'document_type_id' => $workshop->document_type_id,
                    'document_type' => $workshop->document_type,
                    'document_number' => $workshop->document_number,
                    'country_id' => $workshop->country_id,
                    'country' => $workshop->country,
                    'departament_id' => $workshop->departament_id,
                    'departament' => $workshop->departament,
                    'city_id' => $workshop->city_id,
                    'city' => $workshop->city,
                    'address' => $workshop->address,
                    'neighborhood' => $workshop->neighborhood,
                    'email' => $workshop->email,
                    'telephone_number_first' => $workshop->telephone_number_first,
                    'telephone_number_second' => $workshop->telephone_number_second,
                    'created_at' => $this->formatDate($workshop->created_at),
                    'updated_at' => $this->formatDate($workshop->updated_at),
                    'deleted_at' => $workshop->deleted_at
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
