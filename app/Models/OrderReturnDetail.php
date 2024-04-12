<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderReturnDetail extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_return_details';
    protected $fillable = [
        'order_return_id',
        'order_detail_id',
        'status'
    ];

    protected $auditInclude = [
        'order_return_id',
        'order_detail_id',
        'status'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_return_detail_quantities() : HasMany
    {
        return $this->hasMany(OrderReturnDetailQuantity::class, 'order_return_detail_id');
    }

    public function order_return() : BelongsTo
    {
        return $this->belongsTo(OrderReturn::class, 'order_return_id');
    }

    public function order_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }
}
