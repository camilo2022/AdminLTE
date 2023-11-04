<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHistoryPrice extends Model
{
    use HasFactory;
    protected $table = 'product_history_prices';

    protected $fillable = [
        'product_id',
        'price'
    ];
}
