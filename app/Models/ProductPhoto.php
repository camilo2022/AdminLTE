<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ProductPhoto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'product_photos';
    protected $fillable = [
        'product_color_tone_id',
        'name',
        'path'
    ];

    protected $auditInclude = [
        'product_color_tone_id',
        'name',
        'path'
    ];

    public function product_color_tone() : BelongsTo
    {
        return $this->belongsTo(ProductColorTone::class, 'product_color_tone_id');
    }
}
