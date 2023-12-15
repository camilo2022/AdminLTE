<?php

namespace App\Http\Resources\TransferDetail;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransferDetailIndexQueryCollection extends ResourceCollection
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
            'transferDetails' => $this->collection->map(function ($transferDetail) {
                return [
                    'id' => $transferDetail->id,
                    'transfer_id' => $transferDetail->transfer_id,
                    'transfer' => $transferDetail->transfer,
                    'product_id' => $transferDetail->product_id,
                    'product' => $transferDetail->product,
                    'size_id' => $transferDetail->size_id,
                    'size' => $transferDetail->size,
                    'color_id' => $transferDetail->color_id,
                    'color' => $transferDetail->color,
                    'tone_id' => $transferDetail->tone_id,
                    'tone' => $transferDetail->tone,
                    'quantity' => $transferDetail->quantity,
                    'status' => $transferDetail->status,
                    'created_at' => $this->formatDate($transferDetail->created_at),
                    'updated_at' => $this->formatDate($transferDetail->updated_at),
                    'deleted_at' => $transferDetail->deleted_at
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
