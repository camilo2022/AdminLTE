<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DBModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Model extends DBModel implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'models';
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    protected $auditInclude = [
        'name',
        'code',
        'description'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function products() : HasMany
    {
        return $this->hasMany(Product::class, 'model_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'LIKE', '%' . $search . '%')
        ->orWhere('name', 'LIKE', '%' . $search . '%')
        ->orWhere('code', 'LIKE', '%' . $search . '%')
        ->orWhere('description', 'LIKE', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
