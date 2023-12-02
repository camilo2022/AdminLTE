<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'areas';
    protected $fillable = [
        'name',
        'description',
    ];

    public function charges() : HasMany
    {
        return $this->hasMany(Charge::class, 'area_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhereHas('charges',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
