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

class OrderPurchase extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'order_purchases';
    protected $fillable = [
        'workshop_id',
        'purchase_user_id',
        'purchase_status',
        'purchase_date',
        'purchase_observation',
        'payment_status',
        'payment_date'
    ];

    protected $auditInclude = [
        'workshop_id',
        'purchase_user_id',
        'purchase_status',
        'purchase_date',
        'purchase_observation',
        'payment_status',
        'payment_date'
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

    public function order_purchase_details() : HasMany
    {
        return $this->hasMany(OrderPurchaseDetail::class, 'order_purchase_id');
    }

    public function workshop() : BelongsTo
    {
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }

    public function purchase_user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'purchase_user_id');
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
