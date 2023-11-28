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
        'quantity',
        'send_warehouse_id',
        'receive_warehouse_id',
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

    public function send_warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'send_warehouse_id');
    }

    public function receive_warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'receive_warehouse_id');
    }
}
