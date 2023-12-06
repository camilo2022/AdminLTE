<?php

namespace App\Imports\Product;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;

HeadingRowFormatter::default('none');

class ProductImport implements WithMultipleSheets
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
