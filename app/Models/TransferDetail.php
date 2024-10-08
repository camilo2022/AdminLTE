<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TransferDetail extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableModel;

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

    protected $auditInclude = [
        'transfer_id',
        'product_id',
        'size_id',
        'color_id',
        'tone_id',
        'quantity',
        'status'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
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
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhereHas('transfer',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('consecutive', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('product',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('size',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('color',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('code', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('tone',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE',  '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhere('quantity', 'LIKE',  '%' . $search . '%')
        ->orWhere('status', 'LIKE',  '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
