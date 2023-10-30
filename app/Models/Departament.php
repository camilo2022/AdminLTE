<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    protected $table = 'departaments';

    protected $fillable = [
        'name',
        'id_contry'
    ];

    public function country()
    {
          return $this->belongsTo(Country::class, 'id_country');
    }
}
