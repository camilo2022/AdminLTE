<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class ModelPaymentType extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'model_payment_types';
    protected $fillable = [
        'model_type',
        'model_id',
        'payment_type_id'
    ];

    protected $auditInclude = [
        'model_type',
        'model_id',
        'payment_type_id'
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

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }
}
