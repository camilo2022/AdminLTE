<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderPackage extends Model
{
    use HasFactory;
    protected $table = 'order_packages';

    protected $fillable = [
        'order_packing_id',
        'package_id',
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
