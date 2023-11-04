<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHasColor extends Model
{
    use HasFactory;
    protected $table = 'product_has_colors';

    protected $fillable = [
        'product_id',
        'color_id',
    ];
}
