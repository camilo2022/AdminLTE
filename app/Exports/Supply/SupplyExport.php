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
                array_push($array, $fila);
                $i++;
            }

       return $array;
    }
}
