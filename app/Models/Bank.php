<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Bank extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'banks';
    protected $fillable = [
        'name',
        'sector_code',
        'entity_code'
    ];

    protected $auditInclude = [
        'name',
        'sector_code',
        'entity_code'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'like', '%' . $search . '%')
        ->orWhere('name', 'like', '%' . $search . '%')
        ->orWhere('sector_code', 'like', '%' . $search . '%')
        ->orWhere('entity_code', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
