<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class WarehouseUser extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'warehouse_users';
    protected $fillable = [
        'warehouse_id',
        'user_id'
    ];

    protected $auditInclude = [
        'warehouse_id',
        'user_id'
    ];

    public function warehouse() : BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
