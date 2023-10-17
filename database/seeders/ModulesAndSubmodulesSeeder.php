<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleHasRoles;
use App\Models\Submodule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulesAndSubmodulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Configuracion = Module::create(['name' => 'Configuración', 'icon' => 'fas fa-cog']);

        ModuleHasRoles::create([
            'role_id' => 2,
            'module_id' => $Configuracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 3,
            'module_id' => $Configuracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 4,
            'module_id' => $Configuracion->id
        ]);

        Submodule::create([
            'name' => 'Usuarios',
            'url' => '/Dashboard/Users/Index',
            'icon' => 'fas fa-user',
            'module_id' => $Configuracion->id,
            'permission_id' => 2
        ]);

        Submodule::create([
            'name' => 'Accesos',
            'url' => '/Dashboard/RolesAndPermissions/Index',
            'icon' => 'fas fa-key-skeleton-left-right',
            'module_id' => $Configuracion->id,
            'permission_id' => 15
        ]);

        Submodule::create([
            'name' => 'Enrutamientos',
            'url' => '/Dashboard/ModulesAndSubmodules/Index',
            'icon' => 'fas fa-shield-keyhole',
            'module_id' => $Configuracion->id,
            'permission_id' => 20
        ]);
    }
}
