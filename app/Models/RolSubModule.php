<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolSubModule extends Model
{
    protected $table = 'rol_submodules';

    protected $fillable = [
        'id_rol', 
        'id_submodule'
    ];
}
