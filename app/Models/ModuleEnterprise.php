<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleEnterprise extends Model
{
    protected $table='modules_enterprises';

    protected $fillable = [
        'modules_id', 
        'enterprises_id'
    ];
}
