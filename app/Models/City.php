<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class City extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'cities';
    protected $fillable = [
        'name',
        'departament_id'
    ];

    protected $auditInclude = [
        'name',
        'departament_id'
    ];

    public function departament() : BelongsTo
    {
        return $this->belongsTo(Departament::class, 'departament_id');
    }
}
