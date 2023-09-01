<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Access extends Model
{
    protected $table = 'accesses';

    protected $fillable = [
        'name'
    ];

    public function roles()
    {
        return $this->hasMany(SpatieRole::class, 'access_id');
    }
}
