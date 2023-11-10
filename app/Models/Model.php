<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as DBModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends DBModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'models';

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function products() : HasMany
    {
        return $this->hasMany(Product::class, 'model_id');
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
