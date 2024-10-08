<?php

namespace App\Http\Resources\Trademark;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TrademarkIndexQueryCollection extends ResourceCollection
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
            'trademarks' => $this->collection->map(function ($trademark) {
                return [
                    'id' => $trademark->id,
                    'name' => $trademark->name,
                    'code' => $trademark->code,
                    'description' => $trademark->description,
                    'logo' =>  is_null($trademark->logo) ? '' : asset('storage/' . $trademark->logo->path),
                    'created_at' => $this->formatDate($trademark->created_at),
                    'updated_at' => $this->formatDate($trademark->updated_at),
                    'deleted_at' => $trademark->deleted_at
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
