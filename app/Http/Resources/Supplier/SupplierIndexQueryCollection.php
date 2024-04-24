<?php

namespace App\Http\Resources\Supplier;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierIndexQueryCollection extends ResourceCollection
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
            'suppliers' => $this->collection->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'person' => $supplier->person,
                    'name' => $supplier->name,
                    'person_type_id' => $supplier->person_type_id,
                    'person_type' => $supplier->person_type,
                    'document_type_id' => $supplier->document_type_id,
                    'document_type' => $supplier->document_type,
                    'document_number' => $supplier->document_number,
                    'country_id' => $supplier->country_id,
                    'country' => $supplier->country,
                    'departament_id' => $supplier->departament_id,
                    'departament' => $supplier->departament,
                    'city_id' => $supplier->city_id,
                    'city' => $supplier->city,
                    'address' => $supplier->address,
                    'neighborhood' => $supplier->neighborhood,
                    'email' => $supplier->email,
                    'telephone_number_first' => $supplier->telephone_number_first,
                    'telephone_number_second' => $supplier->telephone_number_second,
                    'created_at' => $this->formatDate($supplier->created_at),
                    'updated_at' => $this->formatDate($supplier->updated_at),
                    'deleted_at' => $supplier->deleted_at
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
