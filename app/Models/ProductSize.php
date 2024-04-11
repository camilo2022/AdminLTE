<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class ProductSize extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'product_sizes';
    protected $fillable = [
        'product_id',
        'size_id',
    ];

    protected $auditInclude = [
        'product_id',
        'size_id',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function size() : BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
