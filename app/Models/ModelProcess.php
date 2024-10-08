<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class ModelProcess extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'model_processes';
    protected $fillable = [
        'model_type',
        'model_id',
        'process_id'
    ];

    protected $auditInclude = [
        'model_type',
        'model_id',
        'process_id'
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

    public function process() : BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }
}
