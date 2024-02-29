<?php

namespace App\Http\Resources\OrderSeller;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderSellerPaymentIndexQueryCollection extends ResourceCollection
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
            'orderSellerPayments' => $this->collection->map(function ($orderSellerPayment) {
                return [
                    'id' => $orderSellerPayment->id,
                    'value' => $orderSellerPayment->value,
                    'reference' => $orderSellerPayment->reference,
                    'date' => $this->formatDate($orderSellerPayment->date),
                    'payment_type_id' => $orderSellerPayment->payment_type_id,
                    'payment_type' => $orderSellerPayment->payment_type,
                    'bank_id' => $orderSellerPayment->bank_id,
                    'bank' => $orderSellerPayment->bank,
                    'model' => $orderSellerPayment->model,
                    'files' => $orderSellerPayment->files->map(function ($file) {
                            return [
                                'id' => $file->id,
                                'name' => $file->name,
                                'path' => asset('storage/' . $file->path),
                                'mime' => $file->mime,
                                'extension' => $file->extension,
                                'size' => $file->size,
                                'user_id' => $file->user_id,
                                'user' => $file->user,
                                'metadata' => json_decode($file->path, true)
                            ];
                        }
                    )->toArray(),
                    'created_at' => $this->formatDate($orderSellerPayment->created_at),
                    'updated_at' => $this->formatDate($orderSellerPayment->updated_at),
                    'deleted_at' => $orderSellerPayment->deleted_at,
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
