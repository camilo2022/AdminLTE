<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class OrderDetailQuantity extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

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

    public function order_detail() : BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function size() : BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
