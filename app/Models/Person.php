<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Person extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'people';
    protected $fillable = [
        'model_type',
        'model_id',
        'name',
        'last_name',
        'document_type_id',
        'document_number',
        'country_id',
        'departament_id',
        'city_id',
        'address',
        'neighborhood',
        'email',
        'telephone_number_first',
        'telephone_number_second',
    ];

    protected $auditInclude = [
        'model_type',
        'model_id',
        'name',
        'last_name',
        'document_type_id',
        'document_number',
        'country_id',
        'departament_id',
        'city_id',
        'address',
        'neighborhood',
        'email',
        'telephone_number_first',
        'telephone_number_second',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function model() : MorphTo
    {
        return $this->morphTo();
    }

    public function person_references() : MorphMany
    {
      return $this->morphMany(Person::class, 'model');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'model_id');
    }

    public function document_type() : BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
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
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')
        ->orWhereHas('document_type',
            function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            }
        )
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
