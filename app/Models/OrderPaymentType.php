<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPaymentType extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_payment_types';
    protected $fillable = [
        'order_id',
        'payment_type_id'
    ];

    protected $auditInclude = [
        'order_id',
        'payment_type_id'
    ];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }
}
