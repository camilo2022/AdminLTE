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
        'return_date',
    ];

    protected $auditInclude = [
        'order_id',
        'return_user_id',
        'return_type_id',
        'return_status',
        'return_date',
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
        return $query->where('return_status', 'like',  '%' . $search . '%')
        ->orWhere('return_date', 'like',  '%' . $search . '%')
        ->orWhereHas('return_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like', '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%')
                ->orWhere('last_name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('return_type',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like', '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('order',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'like', '%' . $search . '%')
                ->orWhereHas('client',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%')
                        ->orWhere('document_number', 'like',  '%' . $search . '%');
                    }
                )
                ->orWhereHas('client_branch',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%')
                        ->orWhere('code', 'like',  '%' . $search . '%');
                    }
                )
                ->orWhereHas('transporter',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%');
                    }
                )
                ->orWhereHas('sale_channel',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%');
                    }
                )
                ->orWhere('dispatch', 'like', '%' . $search . '%')
                ->orWhere('dispatch_date', 'like', '%' . $search . '%')
                ->orWhereHas('seller_user',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%')
                        ->orWhere('last_name', 'like',  '%' . $search . '%');
                    }
                )
                ->orWhere('seller_status', 'like', '%' . $search . '%')
                ->orWhere('seller_date', 'like', '%' . $search . '%')
                ->orWhere('seller_observation', 'like', '%' . $search . '%')
                ->orWhereHas('wallet_user',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%')
                        ->orWhere('last_name', 'like',  '%' . $search . '%');
                    }
                )
                ->orWhere('wallet_status', 'like', '%' . $search . '%')
                ->orWhere('wallet_date', 'like', '%' . $search . '%')
                ->orWhere('wallet_observation', 'like', '%' . $search . '%')
                ->orWhere('dispatched_status', 'like', '%' . $search . '%')
                ->orWhere('dispatched_date', 'like', '%' . $search . '%')
                ->orWhereHas('correria',
                    function ($subQuery) use ($search) {
                        $subQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like',  '%' . $search . '%');
                    }
                );
            }
        );
    }
}
