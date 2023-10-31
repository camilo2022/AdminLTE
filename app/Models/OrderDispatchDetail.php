<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderDispatchDetail extends Model
{
    use HasFactory;
    protected $table = 'order_dispatch_details';

    protected $fillable = [
        'order_dispatch_id',
        'order_detail_id',
        '02',
        '04',
        '06',
        '08',
        '10',
        '12',
        '14',
        '16',
        '18',
        '20',
        '22',
        '24',
        '26',
        '28',
        '30',
        '32',
        '34',
        '36',
        '38',
        'XXXS',
        'XXS',
        'XS',
        'S',
        'M',
        'L',
        'XL',
        'XXL',
        'XXXL',
        'order_dispatch_detail_status'
    ];

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
