<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Inventory extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'inventories';
    protected $fillable = [
        'product_id',
        'size_id',
        'warehouse_id',
        'color_id',
        'tone_id',
        'quantity'
    ];

    protected $auditInclude = [
        'product_id',
        'size_id',
        'warehouse_id',
        'color_id',
        'tone_id',
        'quantity'
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

    public function warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function color() : BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function tone() : BelongsTo
    {
        return $this->belongsTo(Tone::class, 'tone_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('quantity', 'LIKE', '%' . $search . '%')
        ->orWhereHas('product',
            function ($subQuery) use ($search) {
                $subQuery->where('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('size',
            function ($subQuery) use ($search) {
                $subQuery->where('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('warehouse',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('color',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('tone',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'LIKE',  '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
