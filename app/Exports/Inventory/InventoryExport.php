<?php

namespace App\Exports\Inventory;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventoryExport implements FromArray, Responsable, WithHeadings, WithTitle
{
    use Exportable;

    private $inventories;

    public function __construct($inventories)
    {
        $this->inventories = $inventories;
    }

    public function headings(): array
    {
        return [
            'id',
            'product_id',
            'product_code',
            'size_id',
            'size_code',
            'warehouse_id',
            'warehouse_code',
            'warehouse_name',
            'color_id',
            'color_code',
            'color_name',
            'tone_id',
            'tone_code',
            'tone_name',
            'quantity'
        ];
    }

    public function title(): string
    {
        return 'Inventarios';
    }

    public function array(): array
    {
       $array = [];
            $i=0;

            foreach($this->inventories as $inventory) {
                array_push($array, [
                    'id' => $inventory->id,
                    'product_id' => $inventory->product_id,
                    'product_code' => $inventory->product->code,
                    'size_id' => $inventory->size_id,
                    'size_code' => $inventory->size->code,
                    'warehouse_id' => $inventory->warehouse_id,
                    'warehouse_code' => $inventory->warehouse->code,
                    'warehouse_name' => $inventory->warehouse->name,
                    'color_id' => $inventory->color_id,
                    'color_code' => $inventory->color->code,
                    'color_name' => $inventory->color->name,
                    'tone_id' => $inventory->tone_id,
                    'tone_code' => $inventory->tone->code,
                    'tone_name' => $inventory->tone->name,
                    'quantity' => $inventory->quantity
                ]);
                $i++;
            }

       return $array;
    }
}
