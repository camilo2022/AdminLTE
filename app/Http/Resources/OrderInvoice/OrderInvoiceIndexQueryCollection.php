<?php

namespace App\Http\Resources\OrderInvoice;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderInvoiceIndexQueryCollection extends ResourceCollection
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
            'orderInvoices' => $this->collection->map(function ($orderInvoice) {
                return [
                    'id' => $orderInvoice->id,
                    'order' => $orderInvoice->order,
                    'client_id' => $orderInvoice->order->client_id,
                    'client' => $orderInvoice->order->client,
                    'client_branch_id' => $orderInvoice->order->client_branch_id,
                    'client_branch' => $orderInvoice->order->client_branch,
                    'dispatch' => $orderInvoice->order->dispatch,
                    'dispatch_date' => $orderInvoice->order->dispatch_date,
                    'seller_user_id' => $orderInvoice->order->seller_user_id,
                    'seller_user' => $orderInvoice->order->seller_user,
                    'seller_status' => $orderInvoice->order->seller_status,
                    'seller_date' => $orderInvoice->order->seller_date,
                    'seller_observation' => $orderInvoice->order->seller_observation,
                    'wallet_user_id' => $orderInvoice->order->wallet_user_id,
                    'wallet_user' => $orderInvoice->order->wallet_user,
                    'wallet_status' => $orderInvoice->order->wallet_status,
                    'wallet_date' => $orderInvoice->order->wallet_date,
                    'wallet_observation' => $orderInvoice->order->wallet_observation,
                    'dispatched_status' => $orderInvoice->order->dispatched_status,
                    'dispatched_date' => $orderInvoice->order->dispatched_date,
                    'correria_id' => $orderInvoice->order->correria_id,
                    'correria' => $orderInvoice->order->correria,
                    'dispatch_user_id' => $orderInvoice->dispatch_user_id,
                    'dispatch_user' => $orderInvoice->dispatch_user,
                    'dispatch_status' => $orderInvoice->dispatch_status,
                    'dispatch_date' => $orderInvoice->dispatch_date,
                    'consecutive' => $orderInvoice->consecutive,
                    'created_at' => $this->formatDate($orderInvoice->created_at),
                    'updated_at' => $this->formatDate($orderInvoice->updated_at),
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
