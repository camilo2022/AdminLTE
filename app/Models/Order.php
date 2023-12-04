<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'client_branch_id',
        'dispatch',
        'dispatch_date',
        'seller_user_id',
        'seller_status',
        'seller_date',
        'seller_observation',
        'wallet_user_id',
        'wallet_status',
        'wallet_date',
        'wallet_observation',
        'dispatched_status',
        'dispatched_date',
        'payment_status',
        'correria_id'
    ];

    public function order_details() : HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function order_dispatches() : HasMany
    {
        return $this->hasMany(OrderDispatch::class, 'order_id');
    }

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function client_branch() : BelongsTo
    {
        return $this->belongsTo(ClientBranch::class, 'client_branch_id');
    }

    public function seller_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function wallet_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'wallet_user_id');
    }

    public function correria() : BelongsTo
    {
        return $this->belongsTo(Correria::class, 'correria_id');
    }
}
