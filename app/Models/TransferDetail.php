<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferDetail extends Model
{
    use HasFactory;
    protected $table = 'transfer_details';

    protected $fillable = [
        'transfer_id',
        'product_id',
        'size_id',
        'color_id',
        'tone_id',
        'quantity',
        'from_warehouse_id',
        'to_warehouse_id',
        'transfer_detail_status'
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

    public function from_warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function to_warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
}
