<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class SubModule extends Model
{

    protected $table='submodules';

    protected $fillable = [
        'name',
        'route',
        'status',
        'module_id',
        'role_id'
    ];
    
    public function roles()
    {
        return $this->belongsTo(SpatieRole::class, 'role_id');
    }
}
