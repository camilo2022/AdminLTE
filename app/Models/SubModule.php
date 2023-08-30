<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class SubModule extends Model
{
    
    protected $table='submodules';
    
    protected $fillable = ['name_submodules', 'route', 'id_module', 'is_active'];

  /*   public function moduless()
    {
        return $this->belongsTo(Requests::class,'id_module');
    } */

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function roles()
    {
        return $this->belongsToMany(SpatieRole::class, 'rol_submodules', 'id_submodule', 'id_rol');
    }    
}
