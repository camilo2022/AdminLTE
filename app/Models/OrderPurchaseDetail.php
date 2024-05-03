<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPurchaseDetail extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_purchase_details';
    protected $fillable = [
        'order_purchase_id',
        'warehouse_id',
        'product_id',
        'color_id',
        'tone_id',
        'price',
        'date',
        'user_id',
        'observation',
        'status'
    ];

    protected $auditInclude = [
        'order_purchase_id',
        'warehouse_id',
        'product_id',
        'color_id',
        'tone_id',
        'price',
        'date',
        'user_id',
        'observation',
        'status'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_purchase_detail_request_quantities() : HasMany
    {
        return $this->hasMany(OrderPurchaseDetailRequestQuantity::class, 'order_purchase_detail_id');
    }

    public function order_purchase() : BelongsTo
    {
        return $this->belongsTo(OrderPurchase::class, 'order_purchase_id');
    }

    public function warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
