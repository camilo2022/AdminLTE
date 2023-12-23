<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clients';
    protected $fillable = [
        'name',
        'person_type_id',
        'client_type_id',
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
        'quota'
    ];

    public function person() : HasOne
    {
        return $this->hasOne(Person::class, 'client_id');
    }

    public function client_branches() : HasMany
    {
        return $this->hasMany(ClientBranch::class, 'client_id');
    }

    public function person_type() : BelongsTo
    {
        return $this->belongsTo(PersonType::class, 'person_type_id');
    }

    public function client_type() : BelongsTo
    {
        return $this->belongsTo(ClientType::class, 'client_type_id');
    }

    public function document_type() : BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function departament_id() : BelongsTo
    {
        return $this->belongsTo(Departament::class, 'departament_id');
    }

    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
