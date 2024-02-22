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

class Support extends Model implements Auditable, HasMedia
{
    use HasFactory, SoftDeletes, Auditing, InteractsWithMedia;

    protected $table = 'supports';
    protected $fillable = [
        'model_id',
        'model_type',
        'value',
        'reference',
        'support_date',
        'bank_id'
    ];

    protected $auditInclude = [
        'model_id',
        'model_type',
        'value',
        'reference',
        'support_date',
        'bank_id'
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
