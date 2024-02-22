<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Warehouse extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'warehouses';
    protected $fillable = [
        'name',
        'code',
        'description',
        'to_discount'
    ];

    protected $auditInclude = [
        'name',
        'code',
        'description',
        'to_discount'
    ];

    public function inventories() : HasMany
    {
        return $this->hasMany(Inventory::class, 'warehouse_id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'warehouse_users', 'warehouse_id', 'user_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('code', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
