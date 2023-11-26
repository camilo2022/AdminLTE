<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductHasSize extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_has_sizes';

    protected $fillable = [
        'product_id',
        'size_id',
    ];
}
