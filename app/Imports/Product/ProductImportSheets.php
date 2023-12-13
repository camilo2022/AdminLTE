<?php

namespace App\Imports\Product;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductImportSheets implements WithMultipleSheets
{
    public function sheets() : array
    {
        return [
            'Products' => new ProductImport(),
            'ProductsSizes' => new ProductSizeImport(),
            'ProductsColorsTones' => new ProductColorToneImport()
        ];
    }
}