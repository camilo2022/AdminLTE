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
            "name_submodules" => "Registro de usuarios",
            "id_module" => 1,
            "route" => "/Dashboard/User/Index"
        ]);
        SubModule::create([
            "name_submodules" => "Usuarios inactivos",
            "id_module" => 1,
            "route" => "/Dashboard/User/Index/Inactivos"
        ]);
        SubModule::create([
            "name_submodules" => "Roles",
            "id_module" => 1,
            "route" => "/Dashboard/Rol/Index"
        ]);
        SubModule::create([
            "name_submodules" => "Permisos",
            "id_module" => 1,
            "route" => "/Dashboard/Permission/Index"
        ]);
        SubModule::create([
            "name_submodules" => "Modulos",
            "id_module" => 1,
            "route" => "/Dashboard/Module/Index"
        ]);
        SubModule::create([
            "name_submodules" => "Sub Modulos",
            "id_module" => 1,
            "route" => "/Dashboard/SubModule/Index"
        ]);
        SubModule::create([
            "name_submodules" => "Empresas",
            "id_module" => 1,
            "route" => "/Dashboard/Enterprises/Index"
        ]);
    }
}
