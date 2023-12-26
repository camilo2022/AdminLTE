<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Pivot
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'product_sizes';
    protected $fillable = [
        'product_id',
        'size_id',
    ];
}
