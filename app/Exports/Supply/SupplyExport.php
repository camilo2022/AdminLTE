<?php

namespace App\Exports\Supply;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SupplyExport implements FromArray, Responsable, WithHeadings, WithTitle
{
    use Exportable;

    private $supplies;

    public function __construct($supplies)
    {
        $this->supplies = $supplies;
    }

    public function headings(): array
    {
        return [
            'id',
            'id proveedor',
            'proveedor',
            'id tipo de insumo',
            'tipo de insumo',
            'id tipo de tela',
            'tipo de tela',
            'id composicion de la tela',
            'composicion de la tela',
            'nombre',
            'codigo',
            'descripcion',
            'cantidad',
            'calidad',
            'ancho',
            'largo',
            'id unidad de medida',
            'unidad de medida',
            'id color',
            'color',
            'id marca',
            'marca',
            'precio con iva',
            'precio sin iva',
        ];
    }

    public function title(): string
    {
        return 'Insumos';
    }

    public function array(): array
    {
       $array = [];
            $i=0;

            foreach($this->supplies as $supply) {
                $fila = [
                    'id' => $supply->id,
                    'id proveedor' => $supply->supplier->id,
                    'proveedor' => $supply->supplier->name,
                    'id tipo de insumo' => $supply->supply_type->id,
                    'tipo de insumo' => $supply->supply_type->name,
                    'id tipo de tela' => $supply->cloth_type ? $supply->cloth_type->id : '',
                    'tipo de tela' => $supply->cloth_type ? $supply->cloth_type->name : '',
                    'id composicion de la tela' => $supply->cloth_composition ? $supply->cloth_composition->id : '',
                    'composicion de la tela' => $supply->cloth_composition ? $supply->cloth_composition->name : '',
                    'nombre' => $supply->name,
                    'codigo' => $supply->code,
                    'descripcion' => $supply->description,
                    'cantidad' => $supply->quantity,
                    'calidad' => $supply->quality,
                    'ancho' => $supply->width,
                    'largo' => $supply->length,
                    'id unidad de medida' => $supply->measurement_unit->id,
                    'unidad de medida' => $supply->measurement_unit->name,
                    'id color' => $supply->color->id,
                    'color' => $supply->color->name,
                    'id marca' => $supply->trademark->id,
                    'marca' => $supply->trademark->name,
                    'precio con iva' => $supply->price_with_vat,
                    'precio sin iva' => $supply->price_without_vat
                ];
                array_push($array, $fila);
                $i++;
            }

       return $array;
    }
}
