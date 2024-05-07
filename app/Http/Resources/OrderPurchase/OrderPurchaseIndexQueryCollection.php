<?php

namespace App\Http\Resources\OrderPurchase;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderPurchaseIndexQueryCollection extends ResourceCollection
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
            'orderPurchases' => $this->collection->map(function ($orderPurchase) {
                return [
                    'id' => $orderPurchase->id,
                    'workshop_id' => $orderPurchase->workshop_id,
                    'workshop' => $orderPurchase->workshop,
                    'purchase_user_id' => $orderPurchase->purchase_user_id,
                    'purchase_user' => $orderPurchase->purchase_user,
                    'purchase_status' => $orderPurchase->purchase_status,
                    'purchase_date' => $orderPurchase->purchase_date,
                    'purchase_observation' => $orderPurchase->purchase_observation,
                    'payment_status' => $orderPurchase->payment_status,
                    'payment_date' => $orderPurchase->payment_date,
                    'invoices' => $orderPurchase->invoices,
                    'payments' => $orderPurchase->payments,
                    'created_at' => $this->formatDate($orderPurchase->created_at),
                    'updated_at' => $this->formatDate($orderPurchase->updated_at),
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
