<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Payment extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'payments';
    protected $fillable = [
        'model_id',
        'model_type',
        'value',
        'reference',
        'date',
        'payment_type_id',
        'bank_id'
    ];

    protected $auditInclude = [
        'model_id',
        'model_type',
        'value',
        'reference',
        'date',
        'payment_type_id',
        'bank_id'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function model() : MorphTo
    {
        return $this->morphTo();
    }

    public function files() : MorphMany
    {
      return $this->morphMany(File::class, 'model');
    }

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function bank() : BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('value', 'LIKE', '%' . $search . '%')
        ->orWhere('reference', 'LIKE', '%' . $search . '%')
        ->orWhere('date', 'LIKE', '%' . $search . '%')
        ->orWhereHas('payment_type_id',
            function ($subQuery) use ($search) {
                $subQuery->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE',  '%' . $search . '%');
            }
        )
        ->orWhereHas('bank_id',
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
