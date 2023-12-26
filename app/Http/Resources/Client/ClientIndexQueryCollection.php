<?php

namespace App\Http\Resources\Client;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientIndexQueryCollection extends ResourceCollection
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
            'clients' => $this->collection->map(function ($clients) {
                return [
                    'id' => $clients->id,
                    'name' => $clients->name,
                    'person_type_id' => $clients->person_type_id,
                    'person_type' => $clients->person_type,
                    'client_type_id' => $clients->client_type_id,
                    'client_type' => $clients->client_type,
                    'document_type_id' => $clients->document_type_id,
                    'document_type' => $clients->document_type,
                    'document_number' => $clients->document_number,
                    'country_id' => $clients->country_id,
                    'country' => $clients->country->name,
                    'departament_id' => $clients->departament_id,
                    'departament' => $clients->departament->name,
                    'city_id' => $clients->city_id,
                    'city' => $clients->city->name,
                    'address' => $clients->address,
                    'neighbourhood' => $clients->neighbourhood,
                    'email' => $clients->email,
                    'telephone_number_first' => $clients->telephone_number_first,
                    'telephone_number_second' => $clients->telephone_number_second,
                    'quota' => $clients->quota,
                    'created_at' => $this->formatDate($clients->created_at),
                    'updated_at' => $this->formatDate($clients->updated_at),
                    'deleted_at' => $clients->deleted_at
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
