<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleHasRoles extends Model
{
    use HasFactory;
    protected $table = 'module_has_roles';

    protected $fillable = [
        'role_id',
        'module_id'
    ];
}
