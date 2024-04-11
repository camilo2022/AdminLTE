<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPackageDetail extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_package_details';
    protected $fillable = [
        'order_package_id',
        'order_dispatch_detail_id',
    ];

    protected $auditInclude = [
        'order_package_id',
        'order_dispatch_detail_id',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_package_detail_quantities() : HasMany
    {
        return $this->hasMany(OrderPackageDetailQuantity::class, 'order_package_detail_id');
    }

    public function order_package() : BelongsTo
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function order_dispatch_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDispatchDetail::class, 'order_dispatch_detail_id');
    }
}
