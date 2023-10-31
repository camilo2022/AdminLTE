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
        'color_id',
        'send_warehouse_id',
        'receive_warehouse_id',
        '02',
        '04',
        '06',
        '08',
        '10',
        '12',
        '14',
        '16',
        '18',
        '20',
        '22',
        '24',
        '26',
        '28',
        '30',
        '32',
        '34',
        '36',
        '38',
        'XXXS',
        'XXS',
        'XS',
        'S',
        'M',
        'L',
        'XL',
        'XXL',
        'XXXL',
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
