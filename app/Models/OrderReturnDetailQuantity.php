<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderReturnDetailQuantity extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_return_details';
    protected $fillable = [
        'order_return_detail_id',
        'order_detail_quantity_id',
        'quantity'
    ];

    protected $auditInclude = [
        'order_return_detail_id',
        'order_detail_quantity_id',
        'quantity'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_return_detail() : BelongsTo
    {
        return $this->belongsTo(OrderReturnDetail::class, 'order_return_detail_id');
    }

    public function order_detail_quantity() : BelongsTo
    {
        return $this->belongsTo(OrderDetailQuantity::class, 'order_detail_quantity_id');
    }
}
