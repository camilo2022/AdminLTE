<?php

namespace App\Http\Resources\OrderSeller;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderSellerIndexQueryCollection extends ResourceCollection
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
            'orderSellers' => $this->collection->map(function ($orderSeller) {
                return [
                    'id' => $orderSeller->id,
                    'client_id' => $orderSeller->client_id,
                    'client' => $orderSeller->client,
                    'client_branch_id' => $orderSeller->client_branch_id,
                    'client_branch' => $orderSeller->client_branch,
                    'dispatch' => $orderSeller->dispatch,
                    'dispatch_date' => $orderSeller->dispatch_date,
                    'seller_user_id' => $orderSeller->seller_user_id,
                    'seller_user' => $orderSeller->seller_user,
                    'seller_status' => $orderSeller->seller_status,
                    'seller_date' => $orderSeller->seller_date,
                    'seller_observation' => $orderSeller->seller_observation,
                    'wallet_user_id' => $orderSeller->wallet_user_id,
                    'wallet_user' => $orderSeller->wallet_user,
                    'wallet_status' => $orderSeller->wallet_status,
                    'wallet_date' => $orderSeller->wallet_date,
                    'wallet_observation' => $orderSeller->wallet_observation,
                    'dispatched_status' => $orderSeller->dispatched_status,
                    'dispatched_date' => $orderSeller->dispatched_date,
                    'correria_id' => $orderSeller->correria_id,
                    'correria' => $orderSeller->correria,
                    'created_at' => $this->formatDate($orderSeller->created_at),
                    'updated_at' => $this->formatDate($orderSeller->updated_at),
                    'deleted_at' => $orderSeller->deleted_at
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
