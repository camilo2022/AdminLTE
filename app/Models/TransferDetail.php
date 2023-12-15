<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transfer_details';
    protected $fillable = [
        'transfer_id',
        'product_id',
        'size_id',
        'color_id',
        'tone_id',
        'quantity',
        'status'
    ];

    public function transfer() : BelongsTo
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function size() : BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
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
        return $query->whereHas('transfer',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('consecutive', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('product',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('code', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('size',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%')
                ->orWhere('code', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('color',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%')
                ->orWhere('code', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('tone',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
