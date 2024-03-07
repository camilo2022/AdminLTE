<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderDispatchDetailQuantity extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_dispatch_detail_quantities';
    protected $fillable = [
        'order_dispatch_detail_id',
        'order_detail_quantity_id',
        'quantity'
    ];

    protected $auditInclude = [
        'order_dispatch_detail_id',
        'order_detail_quantity_id',
        'quantity'
    ];

    public function order_dispatch_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDispatchDetail::class, 'order_dispatch_detail_id');
    }

    public function order_detail_quantity() : BelongsTo
    {
        return $this->belongsTo(OrderDetailQuantity::class, 'order_detail_quantity_id');
    }
}
