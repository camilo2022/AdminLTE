<?php

namespace App\Http\Resources\DocumentType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentTypeIndexQueryCollection extends ResourceCollection
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
            'documentTypes' => $this->collection->map(function ($documentType) {
                return [
                    'id' => $documentType->id,
                    'name' => $documentType->name,
                    'code' => $documentType->code,
                    'created_at' => Carbon::parse($documentType->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($documentType->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $documentType->deleted_at
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
