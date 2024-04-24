<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Supplier extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Auditing;

    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'person_type_id',
        'document_type_id',
        'document_number',
        'country_id',
        'departament_id',
        'city_id',
        'address',
        'neighborhood',
        'email',
        'telephone_number_first',
        'telephone_number_second'
    ];

    protected $auditInclude = [
        'name',
        'person_type_id',
        'document_type_id',
        'document_number',
        'country_id',
        'departament_id',
        'city_id',
        'address',
        'neighborhood',
        'email',
        'telephone_number_first',
        'telephone_number_second'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'retored'
    ];

    public function person_type() : BelongsTo
    {
        return $this->belongsTo(PersonType::class, 'person_type_id');
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
}
