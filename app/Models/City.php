<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'departament_id'
    ];

    public function departament()
    {
          return $this->belongsTo(Departament::class, 'departament_id');
    }

}
