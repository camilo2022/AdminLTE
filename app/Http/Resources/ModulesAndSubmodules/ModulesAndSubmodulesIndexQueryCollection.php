<?php

namespace App\Http\Resources\ModulesAndSubmodules;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModulesAndSubmodulesIndexQueryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'modules' => $this->collection->map(function ($module) {
                return [
                    'id' => $module->id,
                    'module' => $module->name,
                    'type' => $module->type,
                    'icon' => $module->icon,
                    'roles' => $module->roles->map(function ($role) {
                            return [
                                'id' => $role->id,
                                'name' => $role->name
                            ];
                        }
                    )->toArray(),
                    'submodules' => $module->submodules->map(function ($submodule) {
                            return [
                                'id' => $submodule->id,
                                'name' => $submodule->name,
                                'url' => $submodule->url,
                                'icon' => $submodule->icon,
                                'permission' => (object) [
                                    'id' => $submodule->permission->id,
                                    'name' => $submodule->permission->name,
                                    'guard_name' => $submodule->permission->guard_name,
                                    'created_at' => Carbon::parse($submodule->permission->created_at)->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::parse($submodule->permission->updated_at)->format('Y-m-d H:i:s'),
                                ],
                                'created_at' => Carbon::parse($submodule->created_at)->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::parse($submodule->updated_at)->format('Y-m-d H:i:s'),
                            ];
                        }
                    )->toArray(),
                    'created_at' => Carbon::parse($module->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($module->updated_at)->format('Y-m-d H:i:s'),
                ];
            }),
            
            'meta' => [
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
            ],
        ];
    }
}
