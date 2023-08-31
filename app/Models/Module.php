<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Module extends Model
{

    protected $table = 'modules';

    protected $fillable = [
        'name',
        'icon',
        'status'
    ];

    public function submodules()
    {
        return $this->hasMany(Submodule::class, 'module_id');
    }
}
