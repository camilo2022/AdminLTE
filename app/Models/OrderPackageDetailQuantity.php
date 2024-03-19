<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPackageDetailQuantity extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_package_detail_quantities';
    protected $fillable = [
        'order_package_detail_id',
        'order_dispatch_detail_quantity_id',
        'quantity'
    ];

    protected $auditInclude = [
        'order_package_detail_id',
        'order_dispatch_detail_quantity_id',
        'quantity'
    ];

    public function order_package_detail() : BelongsTo
    {
        return $this->belongsTo(OrderPackageDetail::class, 'order_package_detail_id');
    }

    public function order_dispatch_detail_quantity() : BelongsTo
    {
        return $this->belongsTo(OrderDispatchDetailQuantity::class, 'order_dispatch_detail_quantity_id');
    }
}
