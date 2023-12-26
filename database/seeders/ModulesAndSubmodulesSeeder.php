<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Submodule;
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

        $Configuracion->roles()->sync([2, 3, 4]);

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

        $Administracion->roles()->sync([5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]);

        Submodule::create([
            'name' => 'Areas y Cargos',
            'url' => '/Dashboard/AreasAndCharges/Index',
            'icon' => 'fas fa-scale-unbalanced',
            'module_id' => $Administracion->id,
            'permission_id' => 32
        ]);

        Submodule::create([
            'name' => 'Tipos de Documento',
            'url' => '/Dashboard/DocumentTypes/Index',
            'icon' => 'fas fa-id-card',
            'module_id' => $Administracion->id,
            'permission_id' => 40
        ]);

        Submodule::create([
            'name' => 'Tipos de Cliente',
            'url' => '/Dashboard/ClientTypes/Index',
            'icon' => 'fas fa-person-military-to-person',
            'module_id' => $Administracion->id,
            'permission_id' => 48
        ]);

        Submodule::create([
            'name' => 'Tipos de Persona',
            'url' => '/Dashboard/PersonTypes/Index',
            'icon' => 'fas fa-people-simple',
            'module_id' => $Administracion->id,
            'permission_id' => 56
        ]);

        Submodule::create([
            'name' => 'Tipos de Empaque',
            'url' => '/Dashboard/PackageTypes/Index',
            'icon' => 'fas fa-box-open',
            'module_id' => $Administracion->id,
            'permission_id' => 64
        ]);

        Submodule::create([
            'name' => 'Transportadoras',
            'url' => '/Dashboard/Transporters/Index',
            'icon' => 'fas fa-truck',
            'module_id' => $Administracion->id,
            'permission_id' => 72
        ]);

        Submodule::create([
            'name' => 'Metodos de Pago',
            'url' => '/Dashboard/PaymentMethods/Index',
            'icon' => 'fas fa-money-check-dollar-pen',
            'module_id' => $Administracion->id,
            'permission_id' => 80
        ]);

        Submodule::create([
            'name' => 'Empresas',
            'url' => '/Dashboard/Businesses/Index',
            'icon' => 'fas fa-buildings',
            'module_id' => $Administracion->id,
            'permission_id' => 88
        ]);

        Submodule::create([
            'name' => 'Bodegas',
            'url' => '/Dashboard/Warehouses/Index',
            'icon' => 'fas fa-warehouse',
            'module_id' => $Administracion->id,
            'permission_id' => 96
        ]);

        Submodule::create([
            'name' => 'Correrias',
            'url' => '/Dashboard/Correrias/Index',
            'icon' => 'fas fa-rectangle-vertical-history',
            'module_id' => $Administracion->id,
            'permission_id' => 107
        ]);

        Submodule::create([
            'name' => 'Colecciones',
            'url' => '/Dashboard/Collections/Index',
            'icon' => 'fas fa-rectangle-history',
            'module_id' => $Administracion->id,
            'permission_id' => 114
        ]);

        Submodule::create([
            'name' => 'Canales de Venta',
            'url' => '/Dashboard/SaleChannels/Index',
            'icon' => 'fas fa-computer-classic',
            'module_id' => $Administracion->id,
            'permission_id' => 122
        ]);

        $Diseno = Module::create(['name' => 'Diseño', 'icon' => 'fas fa-wand-magic-sparkles']);

        $Diseno->roles()->sync([17, 18, 19, 20, 21, 22, 23]);

        Submodule::create([
            'name' => 'Tallas',
            'url' => '/Dashboard/Sizes/Index',
            'icon' => 'fas fa-arrow-up-9-1',
            'module_id' => $Diseno->id,
            'permission_id' => 130
        ]);

        Submodule::create([
            'name' => 'Marcas',
            'url' => '/Dashboard/Trademarks/Index',
            'icon' => 'fas fa-registered',
            'module_id' => $Diseno->id,
            'permission_id' => 138
        ]);

        Submodule::create([
            'name' => 'Modelos',
            'url' => '/Dashboard/Models/Index',
            'icon' => 'fas fa-scissors',
            'module_id' => $Diseno->id,
            'permission_id' => 146
        ]);

        Submodule::create([
            'name' => 'Lineas',
            'url' => '/Dashboard/ClothingLines/Index',
            'icon' => 'fas fa-clothes-hanger',
            'module_id' => $Diseno->id,
            'permission_id' => 154
        ]);

        Submodule::create([
            'name' => 'Categorización',
            'url' => '/Dashboard/CategoriesAndSubcategories/Index',
            'icon' => 'fas fa-shirt-long-sleeve',
            'module_id' => $Diseno->id,
            'permission_id' => 162
        ]);

        Submodule::create([
            'name' => 'Colores',
            'url' => '/Dashboard/Colors/Index',
            'icon' => 'fas fa-palette',
            'module_id' => $Diseno->id,
            'permission_id' => 170
        ]);

        Submodule::create([
            'name' => 'Tonos',
            'url' => '/Dashboard/Tones/Index',
            'icon' => 'fas fa-paintbrush-fine',
            'module_id' => $Diseno->id,
            'permission_id' => 178
        ]);

        $Existencias = Module::create(['name' => 'Existencias', 'icon' => 'fas fa-hundred-points']);

        $Existencias->roles()->sync([24, 25, 26]);

        Submodule::create([
            'name' => 'Productos',
            'url' => '/Dashboard/Products/Index',
            'icon' => 'fas fa-bookmark',
            'module_id' => $Existencias->id,
            'permission_id' => 186
        ]);

        Submodule::create([
            'name' => 'Inventarios',
            'url' => '/Dashboard/Inventories/Index',
            'icon' => 'fas fa-shelves',
            'module_id' => $Existencias->id,
            'permission_id' => 203
        ]);

        Submodule::create([
            'name' => 'Transferencias',
            'url' => '/Dashboard/Transfers/Index',
            'icon' => 'fas fa-forklift',
            'module_id' => $Existencias->id,
            'permission_id' => 207
        ]);

        $Pedido = Module::create(['name' => 'Ventas', 'icon' => 'fas fa-money-bill']);

        $Pedido->roles()->sync([27]);

        Submodule::create([
            'name' => 'Pedidos',
            'url' => '/Dashboard/Order/Seller/Index',
            'icon' => 'fas fa-receipt',
            'module_id' => $Pedido->id,
            'permission_id' => 225
        ]);

        $Cartera = Module::create(['name' => 'Credito y Cartera', 'icon' => 'fas fa-credit-card']);

        $Cartera->roles()->sync([28, 29, 30]);

        Submodule::create([
            'name' => 'Clientes',
            'url' => '/Dashboard/Clients/Index',
            'icon' => 'fas fa-user-tie',
            'module_id' => $Cartera->id,
            'permission_id' => 242
        ]);

        Submodule::create([
            'name' => 'Gestion',
            'url' => '/Dashboard/Wallet/Index',
            'icon' => 'fas fa-wallet',
            'module_id' => $Cartera->id,
            'permission_id' => 260
        ]);

        Submodule::create([
            'name' => 'Ordenes',
            'url' => '/Dashboard/Order/Wallet/Index',
            'icon' => 'fas fa-traffic-light',
            'module_id' => $Cartera->id,
            'permission_id' => 268
        ]);

        $Despacho = Module::create(['name' => 'Despacho', 'icon' => 'fas fa-mailbox']);

        $Despacho->roles()->sync([31, 32, 33]);

        Submodule::create([
            'name' => 'Filtro',
            'url' => '/Dashboard/Order/Dispatch/Index',
            'icon' => 'fas fa-filter',
            'module_id' => $Despacho->id,
            'permission_id' => 284
        ]);

        Submodule::create([
            'name' => 'Empacado',
            'url' => '/Dashboard/Order/Packed/Index',
            'icon' => 'fas fa-box-open-full',
            'module_id' => $Despacho->id,
            'permission_id' => 302
        ]);

        Submodule::create([
            'name' => 'Facturacion',
            'url' => '/Dashboard/Order/Invoiced/Index',
            'icon' => 'fas fa-file-invoice-dollar',
            'module_id' => $Despacho->id,
            'permission_id' => 315
        ]);

        $Reportes = Module::create(['name' => 'Reportes', 'icon' => 'fas fa-chart-mixed-up-circle-currency']);

        $Reportes->roles()->sync([34]);

        Submodule::create([
            'name' => 'Ventas',
            'url' => '/Dashboard/Reports/Sales/Index',
            'icon' => 'fas fa-hand-holding-dollar',
            'module_id' => $Reportes->id,
            'permission_id' => 319
        ]);

        Submodule::create([
            'name' => 'Despacho',
            'url' => '/Dashboard/Reports/Dispatches/Index',
            'icon' => 'fas fa-hand-holding-box',
            'module_id' => $Reportes->id,
            'permission_id' => 322
        ]);

        Submodule::create([
            'name' => 'Produccion',
            'url' => '/Dashboard/Reports/Productions/Index',
            'icon' => 'fas fa-hand-holding-seedling',
            'module_id' => $Reportes->id,
            'permission_id' => 325
        ]);
    }
}
