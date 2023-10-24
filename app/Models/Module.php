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

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'module_has_roles', 'module_id', 'role_id');
    }

    public function submodules() : HasMany
    {
        return $this->hasMany(Submodule::class, 'module_id');
    }

    public function scopeSearch($query, $search)
    {
        if (is_string($search)) {
            // Filtrar por campos de texto
            return $this->scopeSearchByString($query, $search);
        } 
    }

    public function scopeSearchByString($query, $search)
    {
        return $query->where(
            function ($moduleStringQuery) use ($search) {
                // Busco en la base de datos por coincidencias en "name", "last_name" e "email" y
                // por valores exactos en "document_number"
                $moduleStringQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('icon', 'like', '%' . $search . '%')
                    ->orWhereHas('roles',
                        function ($roleStringQuery) use ($search) {
                            $roleStringQuery->where('name', 'like',  '%' . $search . '%');
                        }
                    )
                    ->orWhereHas('submodules',
                        function ($submoduleStringQuery) use ($search) {
                            $submoduleStringQuery->where('name', 'like',  '%' . $search . '%')
                                ->orWhere('url', 'like',  '%' . $search . '%')
                                ->orWhere('icon', 'like',  '%' . $search . '%');
                        }
                    )
                    ->orWhereHas('submodules.permission',
                        function ($permissionStringQuery) use ($search) {
                            $permissionStringQuery->where('name', 'like',  '%' . $search . '%');
                        }
                    );
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

    public function syncSubmodules($submodules)
    {
        $newSubmoduleIds = collect();

        if (is_array($submodules)) {
            collect($submodules)->each(function ($submodule) use ($newSubmoduleIds) {
                $newSubmoduleIds->push($this->saveSubmodule((object) $submodule));
            });
        } elseif (is_object($submodules)) {
            $newSubmoduleIds->push($this->saveSubmodule((object) $submodules));
        }

        // Eliminar submódulos que no están en $newSubmoduleIds
        $this->submodules()->whereNotIn('id', $newSubmoduleIds)->delete();
    }

    private function saveSubmodule($submodule)
    {
        $submoduleNew = (!isset($submodule->id) || !is_null($submodule->id)) ? new Submodule() : Submodule::findOrFail($submodule->id);

        $submoduleNew->name = is_array($submodule) ? $submodule['submodule'] : $submodule->submodule;
        $submoduleNew->url = is_array($submodule) ? $submodule['url'] : $submodule->url;
        $submoduleNew->icon = is_array($submodule) ? $submodule['icon'] : $submodule->icon;
        $submoduleNew->permission_id = is_array($submodule) ? $submodule['permission_id'] : $submodule->permission_id;
        $submoduleNew->module_id = $this->id;
        $submoduleNew->save();

        return $submoduleNew->id;
    }
}
