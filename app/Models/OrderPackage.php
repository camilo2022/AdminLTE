<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderPackage extends Model implements Auditable
{
    use HasFactory, Auditing, HasUuids;

    protected $table = 'order_packages';
    protected $fillable = [
        'order_packing_id',
        'package_type_id',
        'coding',
        'weight',
        'package_status',
        'package_date',
    ];

    protected $auditInclude = [
        'order_packing_id',
        'package_type_id',
        'coding',
        'weight',
        'package_status',
        'package_date',
    ];

    public function order_package_details() : HasMany
    {
        return $this->hasMany(OrderPackageDetail::class, 'order_package_id');
    }

    public function order_packing() : BelongsTo
    {
        return $this->belongsTo(OrderPacking::class, 'order_packing_id');
    }

    public function package_type() : BelongsTo
    {
        return $this->belongsTo(User::class, 'package_type_id');
    }
}
