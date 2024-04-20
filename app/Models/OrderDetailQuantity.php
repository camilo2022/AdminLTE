<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderDetailQuantity extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_detail_quantities';
    protected $fillable = [
        'order_detail_id',
        'size_id',
        'quantity'
    ];

    protected $auditInclude = [
        'order_detail_id',
        'size_id',
        'quantity'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_return_detail_quantity() : HasMany
    {
        return $this->hasMany(OrderReturnDetailQuantity::class, 'order_detail_quantity_id');
    }

    public function order_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function size() : BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
