<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class OrderDispatch extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_dispatches';
    protected $fillable = [
        'order_id',
        'dispatch_user_id',
        'dispatch_status',
        'dispatch_date',
        'consecutive',
        'payment_status',
        'invoice_user_id',
        'invoice_date'
    ];

    protected $auditInclude = [
        'order_id',
        'dispatch_user_id',
        'dispatch_status',
        'dispatch_date',
        'consecutive',
        'payment_status',
        'invoice_user_id',
        'invoice_date'
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

    public function invoices() : MorphMany
    {
      return $this->morphMany(Invoice::class, 'model');
    }

    public function order_packing() : HasOne
    {
        return $this->hasOne(OrderPacking::class, 'order_dispatch_id');
    }

    public function order_dispatch_details() : HasMany
    {
        return $this->hasMany(OrderDispatchDetail::class, 'order_dispatch_id');
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function dispatch_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatch_user_id');
    }

    public function invoice_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'invoice_user_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('dispatch_status', 'LIKE',  '%' . $search . '%')
        ->orWhere('dispatch_date', 'LIKE',  '%' . $search . '%')
        ->orWhere('consecutive', 'LIKE',  '%' . $search . '%')
        ->orWhere('payment_status', 'LIKE',  '%' . $search . '%')
        ->orWhere('invoice_date', 'LIKE',  '%' . $search . '%')
        ->orWhereHas('dispatch_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('last_name', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('invoice_user',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%')
                ->orWhere('last_name', 'LIKE',  '%' . $search . '%');
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
