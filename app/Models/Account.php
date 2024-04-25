<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Account extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'accounts';
    protected $fillable = [
        'model_type',
        'model_id',
        'account',
        'bank_id'
    ];

    protected $auditInclude = [
        'model_type',
        'model_id',
        'account',
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

    public function bank() : BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
