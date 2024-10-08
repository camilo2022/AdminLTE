<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Order extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'orders';
    protected $fillable = [
        'client_id',
        'client_branch_id',
        'transporter_id',
        'sale_channel_id',
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
        'correria_id'
    ];

    protected $auditInclude = [
        'client_id',
        'client_branch_id',
        'transporter_id',
        'sale_channel_id',
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
        'correria_id'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function payments() : MorphMany
    {
      return $this->morphMany(Payment::class, 'model');
    }

    public function order_returns() : HasMany
    {
        return $this->hasMany(OrderReturn::class, 'order_id');
    }

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

    public function transporter() : BelongsTo
    {
        return $this->belongsTo(Transporter::class, 'transporter_id');
    }

    public function sale_channel() : BelongsTo
    {
        return $this->belongsTo(SaleChannel::class, 'sale_channel_id');
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

    public function payment_types() : MorphToMany
    {
        return $this->morphToMany(PaymentType::class, 'model', 'model_payment_types', 'model_id', 'payment_type_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
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
                ->orWhere('name', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhere('seller_status', 'LIKE', '%' . $search . '%')
        ->orWhere('seller_date', 'LIKE', '%' . $search . '%')
        ->orWhere('seller_observation', 'LIKE', '%' . $search . '%')
        ->orWhereHas('wallet_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%');
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

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
