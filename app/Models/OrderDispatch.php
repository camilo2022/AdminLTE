<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class OrderDispatch extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'order_dispatches';
    protected $fillable = [
        'order_id',
        'dispatch_user_id',
        'dispatch_status',
        'dispatched_date',
        'consecutive'
    ];

    protected $auditInclude = [
        'order_id',
        'dispatch_user_id',
        'dispatch_status',
        'dispatched_date',
        'consecutive'
    ];

    public function order_packing() : HasOne
    {
        return $this->hasOne(OrderPacking::class, 'order_dispatch_id');
    }

    public function order_dispatch_details() : HasMany
    {
        return $this->hasMany(OrderDispatchDetail::class, 'order_dispatch_id');
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function dispatch_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatch_user_id');
    }
}
