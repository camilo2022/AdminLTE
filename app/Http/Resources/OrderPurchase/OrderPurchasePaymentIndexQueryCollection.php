<?php

namespace App\Http\Resources\OrderPurchase;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderPurchasePaymentIndexQueryCollection extends ResourceCollection
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
            'orderPurchasePayments' => $this->collection->map(function ($orderPurchasePayment) {
                return [
                    'id' => $orderPurchasePayment->id,
                    'value' => $orderPurchasePayment->value,
                    'reference' => $orderPurchasePayment->reference,
                    'date' => $this->formatDate($orderPurchasePayment->date),
                    'payment_type_id' => $orderPurchasePayment->payment_type_id,
                    'payment_type' => $orderPurchasePayment->payment_type,
                    'bank_id' => $orderPurchasePayment->bank_id,
                    'bank' => $orderPurchasePayment->bank,
                    'model' => $orderPurchasePayment->model,
                    'files' => $orderPurchasePayment->files->map(function ($file) {
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
                    'created_at' => $this->formatDate($orderPurchasePayment->created_at),
                    'updated_at' => $this->formatDate($orderPurchasePayment->updated_at),
                    'deleted_at' => $orderPurchasePayment->deleted_at,
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
