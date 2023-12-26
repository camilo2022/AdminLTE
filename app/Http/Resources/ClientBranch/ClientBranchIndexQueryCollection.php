<?php

namespace App\Http\Resources\ClientBranch;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientBranchIndexQueryCollection extends ResourceCollection
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
                    'client_id' => $clients->client_id,
                    'client' => $clients->client,
                    'code' => $clients->code,
                    'country_id' => $clients->country_id,
                    'country' => $clients->country->name,
                    'departament_id' => $clients->departament_id,
                    'departament' => $clients->departament->name,
                    'city_id' => $clients->city_id,
                    'city' => $clients->city->name,
                    'address' => $clients->address,
                    'neighbourhood' => $clients->neighbourhood,
                    'description' => $clients->description,
                    'email' => $clients->email,
                    'telephone_number_first' => $clients->telephone_number_first,
                    'telephone_number_second' => $clients->telephone_number_second,
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
