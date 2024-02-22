<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Charge extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'charges';
    protected $fillable = [
        'area_id',
        'name',
        'description'
    ];

    protected $auditInclude = [
        'area_id',
        'name',
        'description'
    ];

    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
