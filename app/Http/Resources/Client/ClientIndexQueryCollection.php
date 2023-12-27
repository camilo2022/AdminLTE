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
            'clients' => $this->collection->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'person_type_id' => $client->person_type_id,
                    'person_type' => $client->person_type,
                    'client_type_id' => $client->client_type_id,
                    'client_type' => $client->client_type,
                    'document_type_id' => $client->document_type_id,
                    'document_type' => $client->document_type,
                    'document_number' => $client->document_number,
                    'country_id' => $client->country_id,
                    'country' => $client->country->name,
                    'departament_id' => $client->departament_id,
                    'departament' => $client->departament->name,
                    'city_id' => $client->city_id,
                    'city' => $client->city->name,
                    'address' => $client->address,
                    'neighbourhood' => $client->neighbourhood,
                    'email' => $client->email,
                    'telephone_number_first' => $client->telephone_number_first,
                    'telephone_number_second' => $client->telephone_number_second,
                    'quota' => $client->quota,
                    'created_at' => $this->formatDate($client->created_at),
                    'updated_at' => $this->formatDate($client->updated_at),
                    'deleted_at' => $client->deleted_at
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
