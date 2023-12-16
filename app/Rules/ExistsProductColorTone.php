<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsProductColorTone implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $productId = $value['product_id'];
        $colorId = $value['color_id'];
        $toneId = $value['tone_id'];

        return DB::table('product_color_tone')
            ->where('product_id', $productId)
            ->where('color_id', $colorId)
            ->where('tone_id', $toneId)
            ->exists();
    }

    public function message()
    {
        return 'El color y tono no estan asignados al producto ingresado.';
    }
}
