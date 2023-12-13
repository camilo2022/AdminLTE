<?php

namespace App\Http\Resources\Tone;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ToneIndexQueryCollection extends ResourceCollection
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
            'tones' => $this->collection->map(function ($tone) {
                return [
                    'id' => $tone->id,
                    'name' => $tone->name,
                    'created_at' => $this->formatDate($tone->created_at),
                    'updated_at' => $this->formatDate($tone->updated_at),
                    'deleted_at' => $tone->deleted_at
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
