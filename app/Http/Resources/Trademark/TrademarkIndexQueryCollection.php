<?php

namespace App\Http\Resources\Trademark;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

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
                    'description' => $trademark->code,
                    'logo' =>  asset('storage/' . $trademark->logo),
                    'created_at' => Carbon::parse($trademark->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($trademark->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $trademark->deleted_at
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
