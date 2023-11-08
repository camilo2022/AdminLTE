<?php

namespace App\Http\Resources\Business;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BusinessIndexQueryCollection extends ResourceCollection
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
            'businesses' => $this->collection->map(function ($business) {
                return [
                    'id' => $business->id,
                    'name' => $business->name,
                    'document_number' => $business->document_number,
                    'telephone_number' => $business->name,
                    'email' => $business->email,
                    'country_id' => $business->country_id,
                    'country' => $business->country->name,
                    'departament_id' => $business->departament_id,
                    'departament' => $business->departament->name,
                    'city_id' => $business->city_id,
                    'city' => $business->city->name,
                    'address' => $business->address,
                    'neighbourhood' => $business->neighbourhood,
                    'description' =>  $business->description,
                    'created_at' => Carbon::parse($business->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($business->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $business->deleted_at
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

