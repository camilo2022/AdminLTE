<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class SaleChannel extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'sale_channels';
    protected $fillable = [
        'name',
        'require_verify_wallet'
    ];

    protected $auditInclude = [
        'name',
        'require_verify_wallet'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function return_types() : BelongsToMany
    {
        return $this->belongsToMany(ReturnType::class, 'sale_channel_return_types', 'sale_channel_id', 'return_type_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('name', 'LIKE', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
