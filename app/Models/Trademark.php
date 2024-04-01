<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Trademark extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'trademarks';
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

    public function logo() : MorphOne
    {
      return $this->morphOne(File::class, 'model');
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class, 'trademark_id');
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
