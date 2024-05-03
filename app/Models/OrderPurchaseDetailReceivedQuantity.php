<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPurchaseDetailReceivedQuantity extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_purchase_detail_received_quantities';
    protected $fillable = [
        'order_purchase_detail_request_quantity_id',
        'quantity'
    ];

    protected $auditInclude = [
        'order_purchase_detail_request_quantity_id',
        'quantity'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_purchase_detail_request_quantity() : BelongsTo
    {
        return $this->belongsTo(OrderPurchaseDetailRequestQuantity::class, 'order_purchase_detail_request_quantity_id');
    }
}
