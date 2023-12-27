<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ProductColorTone extends Pivot implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableModel;

    protected $table = 'product_color_tone';
    protected $fillable = [
        'product_id',
        'color_id',
        'tone_id'
    ];

    protected $auditInclude = [
        'product_id',
        'color_id',
        'tone_id'
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function color() : BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function tone() : BelongsTo
    {
        return $this->belongsTo(Tone::class, 'tone_id');
    }
}
