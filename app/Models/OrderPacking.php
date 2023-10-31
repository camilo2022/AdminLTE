<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderPacking extends Model
{
    use HasFactory;
    protected $table = 'order_packings';

    protected $fillable = [
        'order_dispatch_id',
        'packing_user_id',
        'packing_status',
        'packing_date',
    ];

    public function order_packages() : HasMany
    {
        return $this->hasMany(OrderPackage::class, 'order_packing_id');
    }

    public function order_dispatch() : BelongsTo
    {
        return $this->belongsTo(OrderPacking::class, 'order_dispatch_id');
    }

    public function packing_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'packing_user_id');
    }
}
