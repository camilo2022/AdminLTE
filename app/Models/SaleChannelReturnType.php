<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class SaleChannelReturnType extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'sale_channel_return_types';
    protected $fillable = [
        'sale_channel_id',
        'return_type_id'
    ];

    protected $auditInclude = [
        'sale_channel_id',
        'return_type_id'
    ];

    public function sale_channel() : BelongsTo
    {
        return $this->belongsTo(SaleChannel::class, 'sale_channel_id');
    }

    public function return_type() : BelongsTo
    {
        return $this->belongsTo(ReturnType::class, 'return_type_id');
    }
}
