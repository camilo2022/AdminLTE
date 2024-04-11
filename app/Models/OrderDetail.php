<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderDetail extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_details';
    protected $fillable = [
        'order_id',
        'product_id',
        'color_id',
        'tone_id',
        'price',
        'seller_date',
        'seller_observation',
        'wallet_user_id',
        'wallet_date',
        'dispatched_user_id',
        'dispatched_date',
        'status'
    ];

    protected $auditInclude = [
        'order_id',
        'product_id',
        'color_id',
        'tone_id',
        'price',
        'seller_date',
        'seller_observation',
        'wallet_user_id',
        'wallet_date',
        'dispatched_user_id',
        'dispatched_date',
        'status'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_detail_quantities() : HasMany
    {
        return $this->hasMany(OrderDetailQuantity::class, 'order_detail_id');
    }

    public function dispatch_detail() : HasMany
    {
        return $this->hasMany(OrderDispatchDetail::class, 'order_detail_id');
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

    public function tone() : BelongsTo
    {
        return $this->belongsTo(Tone::class, 'tone_id');
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
