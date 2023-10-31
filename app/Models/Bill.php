<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';

    protected $fillable = [
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
