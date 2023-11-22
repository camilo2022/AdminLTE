<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductHasColor extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_has_colors';

    protected $fillable = [
        'product_id',
        'color_id',
    ];
}
