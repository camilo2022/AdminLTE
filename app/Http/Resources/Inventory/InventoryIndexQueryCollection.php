<?php

namespace App\Http\Resources\Inventory;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryIndexQueryCollection extends ResourceCollection
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
            'inventories' => $this->collection->map(function ($inventory) {
                return [
                    'id' => $inventory->id,
                    'product_id' => $inventory->product_id,
                    'product' => $inventory->product,
                    'size_id' => $inventory->size_id,
                    'size' =>$inventory->size,
                    'warehouse_id' => $inventory->warehouse_id,
                    'warehouse' =>$inventory->warehouse,
                    'color_id' => $inventory->color_id,
                    'color' => $inventory->color,
                    'quantity' => $inventory->quantity,
                    'created_at' => $this->formatDate($inventory->created_at),
                    'updated_at' => $this->formatDate($inventory->updated_at),
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
