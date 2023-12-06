<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductColorTone extends Pivot
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_color_tone';

    protected $fillable = [
        'product_id',
        'color_id',
        'tone_id'
    ];
}
