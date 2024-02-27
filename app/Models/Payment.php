<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements Auditable, HasMedia
{
    use HasFactory, SoftDeletes, Auditing, InteractsWithMedia;

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

    public function model() : MorphTo
    {
        return $this->morphTo();
    }

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function bank() : BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
