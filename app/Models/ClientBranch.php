<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientBranch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'client_branches';
    protected $fillable = [
        'client_id',
        'code',
        'country_id',
        'departament_id',
        'city_id',
        'address',
        'neighborhood',
        'description',
        'email',
        'telephone_number_first',
        'telephone_number_second',
    ];

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function departament() : BelongsTo
    {
        return $this->belongsTo(Departament::class, 'departament_id');
    }

    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->wereHas('client',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            }
        )->orWhere('code', 'like', '%' . $search . '%')
        ->orWhere('document_number', 'like', '%' . $search . '%')
        ->orWhereHas('country',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            }
        )
        ->orWhereHas('departament',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            }
        )
        ->orWhereHas('city',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            }
        )
        ->orWhere('address', 'like', '%' . $search . '%')
        ->orWhere('neighborhood', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhere('email', 'like', '%' . $search . '%')
        ->orWhere('telephone_number_first', 'like', '%' . $search . '%')
        ->orWhere('telephone_number_second', 'like', '%' . $search . '%');
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }
}
