<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Process extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'processes';
    protected $fillable = [
        'name',
        'description',
        'is_cutting_process',
        'is_special_service_process',
        'is_laundry_process',
        'is_decoration_process',
        'is_termination_process'
    ];

    protected $auditInclude = [
        'name',
        'description',
        'is_cutting_process',
        'is_special_service_process',
        'is_laundry_process',
        'is_decoration_process',
        'is_termination_process'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function workshops() : BelongsToMany
    {
        return $this->belongsToMany(Workshop::class, 'model_processes', 'process_id', 'model_id')->where('model_type', Workshop::class);
    }
}
