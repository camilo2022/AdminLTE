<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Province extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'provinces';
    protected $fillable = [
        'departament_id',
        'name'
    ];

    protected $auditInclude = [
        'departament_id',
        'name'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function cities() : HasMany
    {
        return $this->hasMany(City::class, 'province_id');
    }

    public function departament() : BelongsTo
    {
        return $this->belongsTo(Departament::class, 'departament_id');
    }
}
