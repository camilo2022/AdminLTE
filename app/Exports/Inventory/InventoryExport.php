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
    
    private $products;

    public function __construct($products)
    {
        $this->products = $products;
    }
    
    public function headings(): array
    {
        return [
            'id',
            'codigo',
            'observacion',
            'precio',
            'correria',
            'id correria',
            'linea',
            'id linea',
            'categoria',
            'id categoria',
            'subcategoria',
            'id subcategoria',
            'marca',
            'id marca',
            'modelo',
            'id modelo',
            'color',
            'id color',
            'talla',
            'id talla',
        ];
    }
    
    public function title(): string
    {
        return 'Productos';
    }
    
    public function array(): array
    {
       $array = [];
            $i=0;
            
            foreach($this->products as $product) {
                foreach($product->sizes as $size) {
                    foreach($product->colors as $color) {
                        $fila = [
                            'id' => $product->id,
                            'codigo' => $product->code,
                            'observacion' => $product->observation,
                            'precio' => $product->price,
                            'correria' => $product->collection->name,
                            'id correria' => $product->collection_id,
                            'linea' => $product->clothing_line->name,
                            'id linea' => $product->clothing_line_id,
                            'categoria' => $product->category->name,
                            'id categoria' => $product->category_id,
                            'subcategoria' => $product->subcategory->name,
                            'id subcategoria' => $product->subcategory_id,
                            'marca' => $product->trademark->name,
                            'id marca' => $product->trademark_id,
                            'modelo' => $product->model->name,
                            'id modelo' => $product->model_id,
                            'color' => $color->name,
                            'id color' => $color->id,
                            'talla' => $size->name,
                            'id talla' => $size->id,
                        ];
                        array_push($array, $fila);
                        $i++;
                    }
                }
            }
            
       return $array;
    }
}