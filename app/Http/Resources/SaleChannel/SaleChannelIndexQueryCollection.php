<?php

namespace App\Http\Resources\SaleChannel;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleChannelIndexQueryCollection extends ResourceCollection
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
            'saleChannels' => $this->collection->map(function ($saleChannel) {
                return [
                    'id' => $saleChannel->id,
                    'name' => $saleChannel->name,
                    'require_verify_wallet' => $saleChannel->require_verify_wallet,
                    'created_at' => $this->formatDate($saleChannel->created_at),
                    'updated_at' => $this->formatDate($saleChannel->updated_at),
                    'deleted_at' => $saleChannel->deleted_at
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
