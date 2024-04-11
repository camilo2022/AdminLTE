<?php

namespace App\Http\Resources\OrderReturn;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderReturnIndexQueryCollection extends ResourceCollection
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
            'orderReturns' => $this->collection->map(function ($orderReturn) {
                return [
                    'id' => $orderReturn->id,
                    'client_id' => $orderReturn->client_id,
                    'client' => $orderReturn->client,
                    'client_branch_id' => $orderReturn->client_branch_id,
                    'client_branch' => $orderReturn->client_branch,
                    'dispatch' => $orderReturn->dispatch,
                    'dispatch_date' => $orderReturn->dispatch_date,
                    'seller_user_id' => $orderReturn->seller_user_id,
                    'seller_user' => $orderReturn->seller_user,
                    'seller_status' => $orderReturn->seller_status,
                    'seller_date' => $orderReturn->seller_date,
                    'seller_observation' => $orderReturn->seller_observation,
                    'wallet_user_id' => $orderReturn->wallet_user_id,
                    'wallet_user' => $orderReturn->wallet_user,
                    'wallet_status' => $orderReturn->wallet_status,
                    'wallet_date' => $orderReturn->wallet_date,
                    'wallet_observation' => $orderReturn->wallet_observation,
                    'dispatched_status' => $orderReturn->dispatched_status,
                    'dispatched_date' => $orderReturn->dispatched_date,
                    'correria_id' => $orderReturn->correria_id,
                    'correria' => $orderReturn->correria,
                    'created_at' => $this->formatDate($orderReturn->created_at),
                    'updated_at' => $this->formatDate($orderReturn->updated_at),
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
