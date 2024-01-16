<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ReturnType extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableModel;

    protected $table = 'return_types';
    protected $fillable = [
        'name',
    ];

    protected $auditInclude = [
        'name',
    ];

    public function sale_channels() : BelongsToMany
    {
        return $this->belongsToMany(SaleChannel::class, 'sale_channel_return_types', 'return_type_id', 'sale_channel_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
