<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Bill extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'bills';
    protected $fillable = [
        'order_dispatch_id',
        'code',
        'value',
        'route'
    ];

    protected $auditInclude = [
        'order_dispatch_id',
        'code',
        'value',
        'route'
    ];

    public function order_dispatch() : BelongsTo
    {
        return $this->belongsTo(OrderDispatch::class, 'order_dispatch_id');
    }
}
