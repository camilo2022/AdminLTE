<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientBranch extends Model
{
    use HasFactory;
    protected $table = 'client_branches';

    protected $fillable = [
        'client_id',
        'name',
        'telephone_number',
        'email',
        'country_id',
        'departament_id',
        'city_id',
        'address',
        'neighbourhood',
        'description'
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
}
