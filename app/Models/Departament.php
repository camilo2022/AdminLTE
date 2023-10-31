<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departament extends Model
{
    protected $table = 'departaments';

    protected $fillable = [
        'country_id',
        'name'
    ];

    public function cities() : HasMany
    {
        return $this->hasMany(City::class, 'department_id');
    }

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }


}
