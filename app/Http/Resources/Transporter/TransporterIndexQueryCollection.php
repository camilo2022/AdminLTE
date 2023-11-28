<?php

namespace App\Http\Resources\Transporter;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransporterIndexQueryCollection extends ResourceCollection
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
            'transporters' => $this->collection->map(function ($transporter) {
                return [
                    'id' => $transporter->id,
                    'name' => $transporter->name,
                    'document_number' => $transporter->document_number,
                    'telephone_number' => $transporter->name,
                    'email' => $transporter->email,
                    'created_at' => $this->formatDate($transporter->created_at),
                    'updated_at' => $this->formatDate($transporter->updated_at),
                    'deleted_at' => $transporter->deleted_at
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
