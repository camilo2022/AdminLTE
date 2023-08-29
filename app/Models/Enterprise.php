<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    
    protected $table='enterprises';

    protected $fillable = [
        'name_enterprise',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'enterprises_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'modules_enterprises', 'enterprises_id', 'modules_id');
    }

    public function submodules()
    {
        return $this->belongsToMany(SubModule::class, 'submodules_enterprises', 'enterprises_id', 'submodules_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_enterprises', 'enterprises_id', 'user_id');
    }


}
