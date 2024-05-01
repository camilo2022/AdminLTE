<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class MeasurementUnit extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'supplies';
    protected $fillable = [
        'name',
        'code'
    ];

    protected $auditInclude = [
        'name',
        'code'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];
}
