<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'tourism_code',
        'country_code'
    ];

    public function departaments() : HasMany
    {
        return $this->hasMany(Departament::class, 'country_id');
    }
}
