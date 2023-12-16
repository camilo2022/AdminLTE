<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsProductSize implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $productId = $value['product_id'];
        $sizeId = $value['size_id'];

        return DB::table('product_sizes')
            ->where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->exists();
    }

    public function message()
    {
        return 'La talla no esta asignada al producto ingresado.';
    }
}
