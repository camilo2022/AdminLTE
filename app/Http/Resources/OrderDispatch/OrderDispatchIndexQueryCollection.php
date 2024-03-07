<?php

namespace App\Http\Resources\OrderDispatch;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderDispatchIndexQueryCollection extends ResourceCollection
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
            'orderDispatches' => $this->collection->map(function ($orderDispatch) {
                return [
                    'id' => $orderDispatch->id,
                    'client_id' => $orderDispatch->client_id,
                    'client' => $orderDispatch->client,
                    'client_branch_id' => $orderDispatch->client_branch_id,
                    'client_branch' => $orderDispatch->client_branch,
                    'dispatch' => $orderDispatch->dispatch,
                    'dispatch_date' => $orderDispatch->dispatch_date,
                    'seller_user_id' => $orderDispatch->seller_user_id,
                    'seller_user' => $orderDispatch->seller_user,
                    'seller_status' => $orderDispatch->seller_status,
                    'seller_date' => $orderDispatch->seller_date,
                    'seller_observation' => $orderDispatch->seller_observation,
                    'wallet_user_id' => $orderDispatch->wallet_user_id,
                    'wallet_user' => $orderDispatch->wallet_user,
                    'wallet_status' => $orderDispatch->wallet_status,
                    'wallet_date' => $orderDispatch->wallet_date,
                    'wallet_observation' => $orderDispatch->wallet_observation,
                    'dispatched_status' => $orderDispatch->dispatched_status,
                    'dispatched_date' => $orderDispatch->dispatched_date,
                    'correria_id' => $orderDispatch->correria_id,
                    'correria' => $orderDispatch->correria,
                    'order_dispatches' => $orderDispatch->order_dispatches->map(function ($order_dispatch) {
                            return [
                                'id' => $order_dispatch->id,
                                'consecutive' => $order_dispatch->consecutive,
                                'dispatch_status' => $order_dispatch->dispatch_status,
                                'dispatch_user_id' => $order_dispatch->dispatch_user_id,
                                'dispatch_user' => $order_dispatch->dispatch_user,
                                'dispatch_date' => is_null($order_dispatch->dispatch_date) ? '' : $this->formatDate($order_dispatch->dispatch_date),
                                'payment_status' => $order_dispatch->payment_status,
                                'created_at' => $this->formatDate($order_dispatch->created_at),
                                'updated_at' => $this->formatDate($order_dispatch->updated_at),
                            ];
                        }
                    )->toArray(),
                    'order_details' => $orderDispatch->order_details,
                    'created_at' => $this->formatDate($orderDispatch->created_at),
                    'updated_at' => $this->formatDate($orderDispatch->updated_at),
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
