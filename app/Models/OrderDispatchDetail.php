<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderDispatchDetail extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_dispatch_details';
    protected $fillable = [
        'order_dispatch_id',
        'order_detail_id',
        'quantity',
        'order_dispatch_detail_status'
    ];

    protected $auditInclude = [
        'order_dispatch_id',
        'order_detail_id',
        'quantity',
        'order_dispatch_detail_status'
    ];

    public function quantities() : HasMany
    {
        return $this->hasMany(OrderDispatchDetailQuantity::class, 'order_dispatch_detail_id');
    }

    public function order_package_detail() : HasMany
    {
        return $this->hasMany(OrderDispatchDetail::class, 'order_detail_id');
    }

    public function order_dispatch() : BelongsTo
    {
        return $this->belongsTo(OrderDispatch::class, 'order_dispatch_id');
    }

    public function order_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }
}
