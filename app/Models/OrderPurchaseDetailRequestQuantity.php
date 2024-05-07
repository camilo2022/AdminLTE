<?php

namespace App\Models;

use App\Http\Requests\OrderPurchaseDetail\OrderPurchaseDetailReceiveRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPurchaseDetailRequestQuantity extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_purchase_detail_request_quantities';
    protected $fillable = [
        'order_purchase_detail_id',
        'size_id',
        'quantity'
    ];

    protected $auditInclude = [
        'order_purchase_detail_id',
        'size_id',
        'quantity'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_purchase_detail_received_quantities() : HasMany
    {
        return $this->hasMany(OrderPurchaseDetailReceivedQuantity::class, 'order_purchase_detail_request_quantity_id');
    }

    public function order_purchase_detail() : BelongsTo
    {
        return $this->belongsTo(OrderPurchaseDetail::class, 'order_purchase_detail_id');
    }

    public function size() : BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
