<?php

namespace App\Http\Resources\Transfer;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransferIndexQueryCollection extends ResourceCollection
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
            'transfers' => $this->collection->map(function ($transfer) {
                return [
                    'id' => $transfer->id,
                    'consecutive' => $transfer->consecutive,
                    'from_warehouse_id' => $transfer->from_warehouse_id,
                    'from_warehouse' => $transfer->from_warehouse,
                    'from_user_id' => $transfer->from_user_id,
                    'from_user' => $transfer->from_user,
                    'from_date' => $this->formatDate($transfer->from_date),
                    'from_observation' => $transfer->from_observation,
                    'to_warehouse_id' => $transfer->to_warehouse_id,
                    'to_warehouse' => $transfer->to_warehouse,
                    'to_user_id' => $transfer->to_user_id,
                    'to_user' => $transfer->to_user,
                    'to_date' => $transfer->to_date == null ? $transfer->to_date : $this->formatDate($transfer->to_date),
                    'to_observation' => $transfer->to_observation,
                    'status' => $transfer->status,
                    'created_at' => $this->formatDate($transfer->created_at),
                    'updated_at' => $this->formatDate($transfer->updated_at),
                    'deleted_at' => $transfer->deleted_at
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
