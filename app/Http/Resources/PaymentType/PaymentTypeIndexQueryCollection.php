<?php

namespace App\Http\Resources\PaymentType;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentTypeIndexQueryCollection extends ResourceCollection
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
            'paymentTypes' => $this->collection->map(function ($paymentType) {
                return [
                    'id' => $paymentType->id,
                    'name' => $paymentType->name,
                    'code' => $paymentType->code,
                    'require_banks' => $paymentType->require_banks,
                    'created_at' => $this->formatDate($paymentType->created_at),
                    'updated_at' => $this->formatDate($paymentType->updated_at),
                    'deleted_at' => $paymentType->deleted_at
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
