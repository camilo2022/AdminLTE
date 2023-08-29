<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class RolModule extends Model
{
    protected $table = 'rol_modules';

    protected $fillable = [
        'id_rol', 
        'id_module'
    ];
}
