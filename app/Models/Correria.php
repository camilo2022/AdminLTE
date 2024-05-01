<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Correria extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'correrias';
    protected $fillable = [
        'name',
        'code',
        'start_date',
        'end_date'
    ];

    protected $auditInclude = [
        'name',
        'code',
        'start_date',
        'end_date'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function collection() : HasOne
    {
        return $this->hasOne(Collection::class, 'correria_id');
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class, 'correria_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('name', 'LIKE', '%' . $search . '%')
        ->orWhere('code', 'LIKE', '%' . $search . '%')
        ->orWhere('start_date', 'LIKE', '%' . $search . '%')
        ->orWhere('end_date', 'LIKE', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
