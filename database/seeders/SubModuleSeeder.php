<?php

namespace Database\Seeders;

use App\Models\SubModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubModule::create([
            "name" => "Usuarios",
            "route" => "/Dashboard/User/Index",
            "module_id" => 1,
            'role_id' => 2
        ]);
        SubModule::create([
            "name" => "Usuarios Inactivos",
            "route" => "/Dashboard/User/Index/Inactivos",
            "module_id" => 1,
            'role_id' => 2
        ]);
        SubModule::create([
            "name" => "Roles",
            "route" => "/Dashboard/Rol/Index",
            "module_id" => 1,
            'role_id' => 6
        ]);
        // SubModule::create([
        //     "name_submodules" => "Permisos",
        //     "id_module" => 1,
        //     "route" => "/Dashboard/Permission/Index",
        //     "module_id" => 1,
        // ]);
        // SubModule::create([
        //     "name_submodules" => "Modulos",
        //     "id_module" => 1,
        //     "route" => "/Dashboard/Module/Index",
        //     "module_id" => 1,
        // ]);
        // SubModule::create([
        //     "name_submodules" => "Sub Modulos",
        //     "id_module" => 1,
        //     "route" => "/Dashboard/SubModule/Index",
        //     "module_id" => 1,
        // ]);
        // SubModule::create([
        //     "name_submodules" => "Empresas",
        //     "id_module" => 1,
        //     "route" => "/Dashboard/Enterprises/Index",
        //     "module_id" => 1,
        // ]);
    }
}
