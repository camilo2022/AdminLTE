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
            'clientBranches' => $this->collection->map(function ($clientBranch) {
                return [
                    'id' => $clientBranch->id,
                    'client_id' => $clientBranch->client_id,
                    'client' => $clientBranch->client,
                    'code' => $clientBranch->code,
                    'country_id' => $clientBranch->country_id,
                    'country' => $clientBranch->country,
                    'departament_id' => $clientBranch->departament_id,
                    'departament' => $clientBranch->departament,
                    'city_id' => $clientBranch->city_id,
                    'city' => $clientBranch->city,
                    'address' => $clientBranch->address,
                    'neighborhood' => $clientBranch->neighborhood,
                    'description' => $clientBranch->description,
                    'email' => $clientBranch->email,
                    'telephone_number_first' => $clientBranch->telephone_number_first,
                    'telephone_number_second' => $clientBranch->telephone_number_second,
                    'created_at' => $this->formatDate($clientBranch->created_at),
                    'updated_at' => $this->formatDate($clientBranch->updated_at),
                    'deleted_at' => $clientBranch->deleted_at
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
