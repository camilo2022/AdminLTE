<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

class Module extends Model
{
    use HasFactory;
    protected $table = 'modules';

    protected $fillable = [
        'name',
        'type',
        'icon'
    ];

    public function submodules() : HasMany
    {
        return $this->hasMany(Submodule::class, 'module_id');
    }

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'module_has_roles', 'module_id', 'role_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'like',  '%' . $search . '%')
        ->orWhere('name', 'like',  '%' . $search . '%')
        ->orWhere('icon', 'like', '%' . $search . '%')
        ->orWhereHas('roles',
            function ($subQueryRole) use ($search) {
                $subQueryRole->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('submodules',
            function ($subQuerySubmodule) use ($search) {
                $subQuerySubmodule->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%')
                ->orWhere('url', 'like',  '%' . $search . '%')
                ->orWhere('icon', 'like',  '%' . $search . '%');
            }
        )
        ->orWhereHas('submodules.permission',
            function ($subQueryPermission) use ($search) {
                $subQueryPermission->where('id', 'like',  '%' . $search . '%')
                ->orWhere('name', 'like',  '%' . $search . '%');
            }
        );
    }

    public function scopeFilterByDate($query, $start_date, $end_date)
    {
        // Filtro por rango de fechas entre 'start_date' y 'end_date' en el campo 'created_at'
        return $query->whereBetween('created_at', [$start_date, $end_date]);
    }

    public function syncRoles($roles)
    {
        $this->roles()->sync($roles);
    }
}
