<?php

namespace App\Http\Resources\Bank;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BankIndexQueryCollection extends ResourceCollection
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
            'banks' => $this->collection->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'name' => $bank->name,
                    'sector_code' => $bank->sector_code,
                    'entity_code' => $bank->entity_code,
                    'created_at' => $this->formatDate($bank->created_at),
                    'updated_at' => $this->formatDate($bank->updated_at),
                    'deleted_at' => $bank->deleted_at
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
