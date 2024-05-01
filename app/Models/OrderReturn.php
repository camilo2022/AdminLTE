<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderReturn extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_returns';
    protected $fillable = [
        'order_id',
        'return_user_id',
        'return_type_id',
        'return_status',
        'return_date'
    ];

    protected $auditInclude = [
        'order_id',
        'return_user_id',
        'return_type_id',
        'return_status',
        'return_date'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function order_return_details() : HasMany
    {
        return $this->hasMany(OrderReturnDetail::class, 'order_return_id');
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function return_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'return_user_id');
    }

    public function return_type() : BelongsTo
    {
        return $this->belongsTo(ReturnType::class, 'return_type_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('return_status', 'LIKE',  '%' . $search . '%')
        ->orWhere('return_date', 'LIKE',  '%' . $search . '%')
        ->orWhereHas('return_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('last_name', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('return_type',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('order',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhereHas('client',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%')
                        ->orWhere('document_number', 'LIKE',  '%' . $search . '%');
                    }
                )
                ->orWhereHas('client_branch',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%')
                        ->orWhere('code', 'LIKE',  '%' . $search . '%');
                    }
                )
                ->orWhereHas('transporter',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%');
                    }
                )
                ->orWhereHas('sale_channel',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%');
                    }
                )
                ->orWhere('dispatch', 'LIKE', '%' . $search . '%')
                ->orWhere('dispatch_date', 'LIKE', '%' . $search . '%')
                ->orWhereHas('seller_user',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE',  '%' . $search . '%');
                    }
                )
                ->orWhere('seller_status', 'LIKE', '%' . $search . '%')
                ->orWhere('seller_date', 'LIKE', '%' . $search . '%')
                ->orWhere('seller_observation', 'LIKE', '%' . $search . '%')
                ->orWhereHas('wallet_user',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE',  '%' . $search . '%');
                    }
                )
                ->orWhere('wallet_status', 'LIKE', '%' . $search . '%')
                ->orWhere('wallet_date', 'LIKE', '%' . $search . '%')
                ->orWhere('wallet_observation', 'LIKE', '%' . $search . '%')
                ->orWhere('dispatched_status', 'LIKE', '%' . $search . '%')
                ->orWhere('dispatched_date', 'LIKE', '%' . $search . '%')
                ->orWhereHas('correria',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE',  '%' . $search . '%');
                    }
                );
            }
        );
    }
}
