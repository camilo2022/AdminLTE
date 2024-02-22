<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Collection extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'collections';
    protected $fillable = [
        'correria_id',
        'date_definition_start_pilots',
        'date_definition_start_samples',
        'proyection_stop_warehouse',
        'number_samples_include_suitcase'
    ];

    protected $auditInclude = [
        'correria_id',
        'date_definition_start_pilots',
        'date_definition_start_samples',
        'proyection_stop_warehouse',
        'number_samples_include_suitcase'
    ];

    public function correria() : BelongsTo
    {
        return $this->belongsTo(Correria::class, 'correria_id');
    }
}
