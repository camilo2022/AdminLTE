<?php

namespace App\Http\Resources\Wallet;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletIndexQueryCollection extends ResourceCollection
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
            'wallets' => $this->collection->map(function ($wallet) {
                return [
                    'id' => $wallet->id,
                    'order_dispatches' => $wallet->client_branch_orders->pluck('order_dispatches')->flatten(),
                    'client_id' => $wallet->client_id,
                    'client' => $wallet->client,
                    'name' => $wallet->name,
                    'code' => $wallet->code,
                    'country_id' => $wallet->country_id,
                    'country' => $wallet->country,
                    'departament_id' => $wallet->departament_id,
                    'departament' => $wallet->departament,
                    'city_id' => $wallet->city_id,
                    'city' => $wallet->city,
                    'address' => $wallet->address,
                    'neighborhood' => $wallet->neighborhood,
                    'description' => $wallet->description,
                    'email' => $wallet->email,
                    'telephone_number_first' => $wallet->telephone_number_first,
                    'telephone_number_second' => $wallet->telephone_number_second,
                    'created_at' => $this->formatDate($wallet->created_at),
                    'updated_at' => $this->formatDate($wallet->updated_at),
                    'deleted_at' => $wallet->deleted_at
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
