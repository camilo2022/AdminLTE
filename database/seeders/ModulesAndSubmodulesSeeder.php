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

        Submodule::create([
            'name' => 'Tipos de Documento',
            'url' => '/Dashboard/DocumentTypes/Index',
            'icon' => 'fas fa-id-card',
            'module_id' => $Administracion->id,
            'permission_id' => 32
        ]);

        Submodule::create([
            'name' => 'Transportadoras',
            'url' => '/Dashboard/Transporters/Index',
            'icon' => 'fas fa-truck',
            'module_id' => $Administracion->id,
            'permission_id' => 40
        ]);

        Submodule::create([
            'name' => 'Metodos de Pago',
            'url' => '/Dashboard/PaymentMethods/Index',
            'icon' => 'fas fa-money-check-dollar-pen',
            'module_id' => $Administracion->id,
            'permission_id' => 48
        ]);

        Submodule::create([
            'name' => 'Empaques',
            'url' => '/Dashboard/PackageTypes/Index',
            'icon' => 'fas fa-box-open',
            'module_id' => $Administracion->id,
            'permission_id' => 56
        ]);

        Submodule::create([
            'name' => 'Empresas',
            'url' => '/Dashboard/Businesses/Index',
            'icon' => 'fas fa-buildings',
            'module_id' => $Administracion->id,
            'permission_id' => 64
        ]);

        Submodule::create([
            'name' => 'Bodegas',
            'url' => '/Dashboard/Warehouses/Index',
            'icon' => 'fas fa-warehouse',
            'module_id' => $Administracion->id,
            'permission_id' => 72
        ]);

        Submodule::create([
            'name' => 'Correrias',
            'url' => '/Dashboard/Collections/Index',
            'icon' => 'fas fa-rectangle-vertical-history',
            'module_id' => $Administracion->id,
            'permission_id' => 83
        ]);

        $Insumos = Module::create(['name' => 'Insumos', 'icon' => 'fas fa-reel']);

        ModuleHasRoles::create([
            'role_id' => 12,
            'module_id' => $Insumos->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 13,
            'module_id' => $Insumos->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 14,
            'module_id' => $Insumos->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 15,
            'module_id' => $Insumos->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 16,
            'module_id' => $Insumos->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 17,
            'module_id' => $Insumos->id
        ]);

        Submodule::create([
            'name' => 'Tallas',
            'url' => '/Dashboard/Sizes/Index',
            'icon' => 'fas fa-arrow-up-9-1',
            'module_id' => $Insumos->id,
            'permission_id' => 90
        ]);

        Submodule::create([
            'name' => 'Marcas',
            'url' => '/Dashboard/Trademarks/Index',
            'icon' => 'fas fa-registered',
            'module_id' => $Insumos->id,
            'permission_id' => 98
        ]);

        Submodule::create([
            'name' => 'Modelos',
            'url' => '/Dashboard/Models/Index',
            'icon' => 'fas fa-scissors',
            'module_id' => $Insumos->id,
            'permission_id' => 106
        ]);

        Submodule::create([
            'name' => 'Lineas',
            'url' => '/Dashboard/ClothingLines/Index',
            'icon' => 'fas fa-clothes-hanger',
            'module_id' => $Insumos->id,
            'permission_id' => 114
        ]);

        Submodule::create([
            'name' => 'Categorización',
            'url' => '/Dashboard/CategoriesAndSubcategories/Index',
            'icon' => 'fas fa-shirt-long-sleeve',
            'module_id' => $Insumos->id,
            'permission_id' => 122
        ]);

        Submodule::create([
            'name' => 'Colores',
            'url' => '/Dashboard/Colors/Index',
            'icon' => 'fas fa-palette',
            'module_id' => $Insumos->id,
            'permission_id' => 130
        ]);

        $Existencias = Module::create(['name' => 'Existencias', 'icon' => 'fas fa-hundred-points']);

        ModuleHasRoles::create([
            'role_id' => 18,
            'module_id' => $Existencias->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 19,
            'module_id' => $Existencias->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 20,
            'module_id' => $Existencias->id
        ]);

        Submodule::create([
            'name' => 'Productos',
            'url' => '/Dashboard/Products/Index',
            'icon' => 'fas fa-bookmark',
            'module_id' => $Existencias->id,
            'permission_id' => 138
        ]);

        Submodule::create([
            'name' => 'Inventarios',
            'url' => '/Dashboard/Inventories/Index',
            'icon' => 'fas fa-shelves',
            'module_id' => $Existencias->id,
            'permission_id' => 150
        ]);

        Submodule::create([
            'name' => 'Transferencias',
            'url' => '/Dashboard/Transfers/Index',
            'icon' => 'fas fa-forklift',
            'module_id' => $Existencias->id,
            'permission_id' => 154
        ]);

        $Pedido = Module::create(['name' => 'Ventas', 'icon' => 'fas fa-money-bill']);

        ModuleHasRoles::create([
            'role_id' => 21,
            'module_id' => $Pedido->id
        ]);

        Submodule::create([
            'name' => 'Pedidos',
            'url' => '/Dashboard/Order/Seller/Index',
            'icon' => 'fas fa-receipt',
            'module_id' => $Pedido->id,
            'permission_id' => 171
        ]);

        $Cartera = Module::create(['name' => 'Credito y Cartera', 'icon' => 'fas fa-credit-card']);

        ModuleHasRoles::create([
            'role_id' => 22,
            'module_id' => $Cartera->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 23,
            'module_id' => $Cartera->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 24,
            'module_id' => $Cartera->id
        ]);

        Submodule::create([
            'name' => 'Clientes',
            'url' => '/Dashboard/Clients/Index',
            'icon' => 'fas fa-user-tie',
            'module_id' => $Cartera->id,
            'permission_id' => 188
        ]);

        Submodule::create([
            'name' => 'Gestion',
            'url' => '/Dashboard/Wallet/Index',
            'icon' => 'fas fa-wallet',
            'module_id' => $Cartera->id,
            'permission_id' => 206
        ]);

        Submodule::create([
            'name' => 'Ordenes',
            'url' => '/Dashboard/Order/Wallet/Index',
            'icon' => 'fas fa-traffic-light',
            'module_id' => $Cartera->id,
            'permission_id' => 214
        ]);

        $Despacho = Module::create(['name' => 'Despacho', 'icon' => 'fas fa-mailbox']);

        ModuleHasRoles::create([
            'role_id' => 25,
            'module_id' => $Despacho->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 26,
            'module_id' => $Despacho->id
        ]);

        ModuleHasRoles::create([
            'role_id' => 27,
            'module_id' => $Despacho->id
        ]);

        Submodule::create([
            'name' => 'Filtro',
            'url' => '/Dashboard/Order/Dispatch/Index',
            'icon' => 'fas fa-filter',
            'module_id' => $Despacho->id,
            'permission_id' => 230
        ]);

        Submodule::create([
            'name' => 'Empacado',
            'url' => '/Dashboard/Order/Packed/Index',
            'icon' => 'fas fa-box-open-full',
            'module_id' => $Despacho->id,
            'permission_id' => 248
        ]);

        Submodule::create([
            'name' => 'Facturacion',
            'url' => '/Dashboard/Order/Invoiced/Index',
            'icon' => 'fas fa-file-invoice-dollar',
            'module_id' => $Despacho->id,
            'permission_id' => 261
        ]);

        $Reportes = Module::create(['name' => 'Reportes', 'icon' => 'fas fa-chart-mixed-up-circle-currency']);

        ModuleHasRoles::create([
            'role_id' => 28,
            'module_id' => $Reportes->id
        ]);

        Submodule::create([
            'name' => 'Ventas',
            'url' => '/Dashboard/Reports/Sales/Index',
            'icon' => 'fas fa-hand-holding-dollar',
            'module_id' => $Reportes->id,
            'permission_id' => 265
        ]);

        Submodule::create([
            'name' => 'Despacho',
            'url' => '/Dashboard/Reports/Dispatches/Index',
            'icon' => 'fas fa-hand-holding-box',
            'module_id' => $Reportes->id,
            'permission_id' => 268
        ]);

        Submodule::create([
            'name' => 'Produccion',
            'url' => '/Dashboard/Reports/Productions/Index',
            'icon' => 'fas fa-hand-holding-seedling',
            'module_id' => $Reportes->id,
            'permission_id' => 271
        ]);
    }
}
