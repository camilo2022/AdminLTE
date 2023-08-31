<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleHasModel extends Model
{
    protected $table = 'modules_has_models';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'module_id'
    ];
}
