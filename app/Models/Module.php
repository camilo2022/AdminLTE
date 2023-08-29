<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Module extends Model
{
    
    protected $table='modules';
    
    protected $fillable = [
        'name_modules', 
        'is_active', 
        'icon_modules'
    ];

    public function roles()
    {
        return $this->belongsToMany(SpatieRole::class, 'rol_modules', 'id_module', 'id_rol');
    }    

    public function enterprises()
    {
        return $this->belongsToMany(Enterprise::class, 'modules_enterprises', 'modules_id', 'enterprises_id');
    }

    public function submodules()
    {
        return $this->hasMany(Submodule::class, 'id_module');
    } 


 

}
