<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventories';

    protected $fillable = [
        'product_id',
        'size_id',
        'warehouse_id',
        'color_id',
        'quantity'
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

    public function scopeSearch($query, $search)
    {
        return $query->where('quantity', 'like', '%' . $search . '%')
        ->orWhereHas('product',
            function ($subQuery) use ($search) {
                $subQuery->where('code', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('size',
            function ($subQuery) use ($search) {
                $subQuery->where('code', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('warehouse',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%')
                ->orWhere('code', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('color',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like',  '%' . $search . '%')
                ->orWhere('code', 'like',  '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
