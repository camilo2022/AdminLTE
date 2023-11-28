<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'price',
        'seller_date',
        'seller_observation',
        'wallet_user_id',
        'wallet_date',
        'dispatched_user_id',
        'dispatched_date',
        'order_detail_status'
    ];


    public function order_dispatch_detail() : HasOne
    {
        return $this->hasOne(OrderDispatchDetail::class, 'order_detail_id');
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function color() : BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function wallet_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'wallet_user_id');
    }

    public function dispatched_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatched_user_id');
    }
}
