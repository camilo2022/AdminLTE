<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'id_departament'
    ];

    public function departament()
    {
          return $this->belongsTo(Departament::class, 'id_departament');
    }

}
