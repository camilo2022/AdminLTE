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
            'icon' => 'fas fa-users',
            'module_id' => $Configuracion->id,
            'permission_id' => 2
        ]);

        Submodule::create([
            'name' => 'Accesos',
            'url' => '/Dashboard/RolesAndPermissions/Index',
            'icon' => 'fas fa-key-skeleton-left-right',
            'module_id' => $Configuracion->id,
            'permission_id' => 18
        ]);

        Submodule::create([
            'name' => 'Enrutamientos',
            'url' => '/Dashboard/ModulesAndSubmodules/Index',
            'icon' => 'fas fa-shield-keyhole',
            'module_id' => $Configuracion->id,
            'permission_id' => 25
        ]);

        $Administracion = Module::create(['name' => 'Administración', 'icon' => 'fas fa-folder']);

        ModuleHasRoles::create([
            'role_id' => 5,
            'module_id' => $Administracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 6,
            'module_id' => $Administracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 7,
            'module_id' => $Administracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 8,
            'module_id' => $Administracion->id
        ]);       

        ModuleHasRoles::create([
            'role_id' => 9,
            'module_id' => $Administracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 10,
            'module_id' => $Administracion->id
        ]);        

        ModuleHasRoles::create([
            'role_id' => 11,
            'module_id' => $Administracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 12,
            'module_id' => $Administracion->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 13,
            'module_id' => $Administracion->id
        ]);

        Submodule::create([
            'name' => 'Correrias',
            'url' => '/Dashboard/Collections/Index',
            'icon' => 'fa-solid fa-rectangle-vertical-history',
            'module_id' => $Administracion->id,
            'permission_id' => 32
        ]);

        Submodule::create([
            'name' => 'Empaques',
            'url' => '/Dashboard/Packages/Index',
            'icon' => 'fa-solid fa-box-open',
            'module_id' => $Administracion->id,
            'permission_id' => 39
        ]);

        Submodule::create([
            'name' => 'Marcas',
            'url' => '/Dashboard/Trademarks/Index',
            'icon' => 'fa-solid fa-registered',
            'module_id' => $Administracion->id,
            'permission_id' => 47
        ]);

        Submodule::create([
            'name' => 'Empresas',
            'url' => '/Dashboard/Businesses/Index',
            'icon' => 'fa-solid fa-buildings',
            'module_id' => $Administracion->id,
            'permission_id' => 54
        ]);

        Submodule::create([
            'name' => 'Modelos',
            'url' => '/Dashboard/Models/Index',
            'icon' => 'fa-solid fa-scissors',
            'module_id' => $Administracion->id,
            'permission_id' => 61
        ]);

        Submodule::create([
            'name' => 'Lineas',
            'url' => '/Dashboard/ClothingLines/Index',
            'icon' => 'fa-solid fa-family',
            'module_id' => $Administracion->id,
            'permission_id' => 68
        ]);

        Submodule::create([
            'name' => 'Categorización',
            'url' => '/Dashboard/CategoriesAndSubcategories/Index',
            'icon' => 'fa-solid fa-shirt-long-sleeve',
            'module_id' => $Administracion->id,
            'permission_id' => 75
        ]);

        Submodule::create([
            'name' => 'Bodegas',
            'url' => '/Dashboard/Warehouses/Index',
            'icon' => 'fa-solid fa-warehouse',
            'module_id' => $Administracion->id,
            'permission_id' => 82
        ]);

        Submodule::create([
            'name' => 'Colores',
            'url' => '/Dashboard/Colors/Index',
            'icon' => 'fa-solid fa-palette',
            'module_id' => $Administracion->id,
            'permission_id' => 89
        ]);

        $Inventarios = Module::create(['name' => 'Inventarios', 'icon' => 'fas fa-scanner-gun']);

        ModuleHasRoles::create([
            'role_id' => 14,
            'module_id' => $Inventarios->id
        ]);
        
        ModuleHasRoles::create([
            'role_id' => 15,
            'module_id' => $Inventarios->id
        ]);
        
        ModuleHasRoles::create([
            'role_id' => 16,
            'module_id' => $Inventarios->id
        ]);

        Submodule::create([
            'name' => 'Productos',
            'url' => '/Dashboard/Products/Index',
            'icon' => 'fa-solid fa-bookmark',
            'module_id' => $Inventarios->id,
            'permission_id' => 96
        ]);

        Submodule::create([
            'name' => 'Unidades',
            'url' => '/Dashboard/Inventories/Index',
            'icon' => 'fa-solid fa-shelves',
            'module_id' => $Inventarios->id,
            'permission_id' => 103
        ]);

        Submodule::create([
            'name' => 'Transferencias',
            'url' => '/Dashboard/Transfers/Index',
            'icon' => 'fa-solid fa-forklift',
            'module_id' => $Inventarios->id,
            'permission_id' => 111
        ]);

        $Pedido = Module::create(['name' => 'Ventas', 'icon' => 'fas fa-money-bill']);

        ModuleHasRoles::create([
            'role_id' => 17,
            'module_id' => $Pedido->id
        ]);
        
        ModuleHasRoles::create([
            'role_id' => 18,
            'module_id' => $Pedido->id
        ]);

        Submodule::create([
            'name' => 'Clientes',
            'url' => '/Dashboard/Clients/Index',
            'icon' => 'fa-solid fa-user-secret',
            'module_id' => $Pedido->id,
            'permission_id' => 128
        ]);

        Submodule::create([
            'name' => 'Pedido',
            'url' => '/Dashboard/Order/Seller/Index',
            'icon' => 'fa-solid fa-receipt',
            'module_id' => $Pedido->id,
            'permission_id' => 142
        ]);

        $Cartera = Module::create(['name' => 'Cartera', 'icon' => 'fas fa-wallet']);

        ModuleHasRoles::create([
            'role_id' => 19,
            'module_id' => $Cartera->id
        ]);
        
        ModuleHasRoles::create([
            'role_id' => 20,
            'module_id' => $Cartera->id
        ]);
    }
}
