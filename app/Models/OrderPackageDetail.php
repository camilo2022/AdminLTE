<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPackageDetail extends Model
{
    use HasFactory;
    protected $table = 'order_package_details';

    protected $fillable = [
        'order_package_id',
        'order_dispatch_detail_id',
        'quantity',
    ];

    public function order_package() : BelongsTo
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function order_dispatch_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDispatchDetail::class, 'order_dispatch_detail_id');
    }
}
