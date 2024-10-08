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

        $Administracion->roles()->sync([5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]);

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
            'permission_id' => 67
        ]);

        Submodule::create([
            'name' => 'Tipos de Devoluciones',
            'url' => '/Dashboard/ReturnTypes/Index',
            'icon' => 'fas fa-message-dots',
            'module_id' => $Administracion->id,
            'permission_id' => 75
        ]);

        Submodule::create([
            'name' => 'Transportadoras',
            'url' => '/Dashboard/Transporters/Index',
            'icon' => 'fas fa-truck',
            'module_id' => $Administracion->id,
            'permission_id' => 83
        ]);

        Submodule::create([
            'name' => 'Bancos',
            'url' => '/Dashboard/Banks/Index',
            'icon' => 'fas fa-building-columns',
            'module_id' => $Administracion->id,
            'permission_id' => 91
        ]);

        Submodule::create([
            'name' => 'Metodos de Pago',
            'url' => '/Dashboard/PaymentTypes/Index',
            'icon' => 'fas fa-money-check-dollar-pen',
            'module_id' => $Administracion->id,
            'permission_id' => 99
        ]);

        Submodule::create([
            'name' => 'Empresas',
            'url' => '/Dashboard/Businesses/Index',
            'icon' => 'fas fa-buildings',
            'module_id' => $Administracion->id,
            'permission_id' => 107
        ]);

        Submodule::create([
            'name' => 'Bodegas',
            'url' => '/Dashboard/Warehouses/Index',
            'icon' => 'fas fa-warehouse',
            'module_id' => $Administracion->id,
            'permission_id' => 115
        ]);

        Submodule::create([
            'name' => 'Correrias',
            'url' => '/Dashboard/CorreriasAndCollections/Index',
            'icon' => 'fas fa-rectangle-vertical-history',
            'module_id' => $Administracion->id,
            'permission_id' => 126
        ]);

        Submodule::create([
            'name' => 'Canales de Venta',
            'url' => '/Dashboard/SaleChannels/Index',
            'icon' => 'fas fa-computer-classic',
            'module_id' => $Administracion->id,
            'permission_id' => 133
        ]);

        Submodule::create([
            'name' => 'Talleres',
            'url' => '/Dashboard/Workshops/Index',
            'icon' => 'fas fa-house-laptop',
            'module_id' => $Administracion->id,
            'permission_id' => 144
        ]);

        Submodule::create([
            'name' => 'Proveedores',
            'url' => '/Dashboard/Suppliers/Index',
            'icon' => 'fas fa-truck-field',
            'module_id' => $Administracion->id,
            'permission_id' => 157
        ]);

        $Diseno = Module::create(['name' => 'Diseño', 'icon' => 'fas fa-wand-magic-sparkles']);

        $Diseno->roles()->sync([20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]);

        Submodule::create([
            'name' => 'Tipos de Insumos',
            'url' => '/Dashboard/SupplyTypes/Index',
            'icon' => 'fas fa-parachute-box',
            'module_id' => $Diseno->id,
            'permission_id' => 166
        ]);

        Submodule::create([
            'name' => 'Tipos de Telas',
            'url' => '/Dashboard/ClothTypes/Index',
            'icon' => 'fas fa-scroll',
            'module_id' => $Diseno->id,
            'permission_id' => 174
        ]);

        Submodule::create([
            'name' => 'Composicion de Telas',
            'url' => '/Dashboard/ClothCompositions/Index',
            'icon' => 'fas fa-crown',
            'module_id' => $Diseno->id,
            'permission_id' => 182
        ]);

        Submodule::create([
            'name' => 'Insumos',
            'url' => '/Dashboard/Supplies/Index',
            'icon' => 'fas fa-thumbtack',
            'module_id' => $Diseno->id,
            'permission_id' => 190
        ]);

        Submodule::create([
            'name' => 'Tallas',
            'url' => '/Dashboard/Sizes/Index',
            'icon' => 'fas fa-arrow-up-9-1',
            'module_id' => $Diseno->id,
            'permission_id' => 203
        ]);

        Submodule::create([
            'name' => 'Marcas',
            'url' => '/Dashboard/Trademarks/Index',
            'icon' => 'fas fa-registered',
            'module_id' => $Diseno->id,
            'permission_id' => 211
        ]);

        Submodule::create([
            'name' => 'Modelos',
            'url' => '/Dashboard/Models/Index',
            'icon' => 'fas fa-scissors',
            'module_id' => $Diseno->id,
            'permission_id' => 219
        ]);

        Submodule::create([
            'name' => 'Lineas',
            'url' => '/Dashboard/ClothingLines/Index',
            'icon' => 'fas fa-clothes-hanger',
            'module_id' => $Diseno->id,
            'permission_id' => 227
        ]);

        Submodule::create([
            'name' => 'Categorización',
            'url' => '/Dashboard/CategoriesAndSubcategories/Index',
            'icon' => 'fas fa-shirt-long-sleeve',
            'module_id' => $Diseno->id,
            'permission_id' => 235
        ]);

        Submodule::create([
            'name' => 'Colores',
            'url' => '/Dashboard/Colors/Index',
            'icon' => 'fas fa-palette',
            'module_id' => $Diseno->id,
            'permission_id' => 243
        ]);

        Submodule::create([
            'name' => 'Tonos',
            'url' => '/Dashboard/Tones/Index',
            'icon' => 'fas fa-paintbrush-fine',
            'module_id' => $Diseno->id,
            'permission_id' => 251
        ]);

        $Existencias = Module::create(['name' => 'Existencias', 'icon' => 'fas fa-hundred-points']);

        $Existencias->roles()->sync([31, 32, 33]);

        Submodule::create([
            'name' => 'Productos',
            'url' => '/Dashboard/Products/Index',
            'icon' => 'fas fa-bookmark',
            'module_id' => $Existencias->id,
            'permission_id' => 259
        ]);

        Submodule::create([
            'name' => 'Inventarios',
            'url' => '/Dashboard/Inventories/Index',
            'icon' => 'fas fa-shelves',
            'module_id' => $Existencias->id,
            'permission_id' => 276
        ]);

        Submodule::create([
            'name' => 'Transferencias',
            'url' => '/Dashboard/Transfers/Index',
            'icon' => 'fas fa-forklift',
            'module_id' => $Existencias->id,
            'permission_id' => 280
        ]);

        $Pedido = Module::create(['name' => 'Ventas', 'icon' => 'fas fa-money-bill']);

        $Pedido->roles()->sync([34, 35]);

        Submodule::create([
            'name' => 'Compras',
            'url' => '/Dashboard/Orders/Purchase/Index',
            'icon' => 'fas fa-cart-shopping',
            'module_id' => $Pedido->id,
            'permission_id' => 298
        ]);

        Submodule::create([
            'name' => 'Pedidos',
            'url' => '/Dashboard/Orders/Seller/Index',
            'icon' => 'fas fa-receipt',
            'module_id' => $Pedido->id,
            'permission_id' => 325
        ]);

        $Cartera = Module::create(['name' => 'Credito y Cartera', 'icon' => 'fas fa-credit-card']);

        $Cartera->roles()->sync([36, 37, 38]);

        Submodule::create([
            'name' => 'Clientes',
            'url' => '/Dashboard/Clients/Index',
            'icon' => 'fas fa-user-tie',
            'module_id' => $Cartera->id,
            'permission_id' => 350
        ]);

        Submodule::create([
            'name' => 'Gestion',
            'url' => '/Dashboard/Wallets/Index',
            'icon' => 'fas fa-wallet',
            'module_id' => $Cartera->id,
            'permission_id' => 382
        ]);

        Submodule::create([
            'name' => 'Ordenes',
            'url' => '/Dashboard/Orders/Wallet/Index',
            'icon' => 'fas fa-traffic-light',
            'module_id' => $Cartera->id,
            'permission_id' => 386
        ]);

        $Despacho = Module::create(['name' => 'Despacho', 'icon' => 'fas fa-mailbox']);

        $Despacho->roles()->sync([39, 40, 41]);

        Submodule::create([
            'name' => 'Filtro',
            'url' => '/Dashboard/Orders/Dispatch/Index',
            'icon' => 'fas fa-filter',
            'module_id' => $Despacho->id,
            'permission_id' => 405
        ]);

        Submodule::create([
            'name' => 'Empacado',
            'url' => '/Dashboard/Orders/Packed/Index',
            'icon' => 'fas fa-box-open-full',
            'module_id' => $Despacho->id,
            'permission_id' => 423
        ]);

        Submodule::create([
            'name' => 'Facturacion',
            'url' => '/Dashboard/Orders/Invoice/Index',
            'icon' => 'fas fa-file-invoice-dollar',
            'module_id' => $Despacho->id,
            'permission_id' => 437
        ]);

        Submodule::create([
            'name' => 'Devolucion',
            'url' => '/Dashboard/Orders/Return/Index',
            'icon' => 'fas fa-scanner-keyboard',
            'module_id' => $Despacho->id,
            'permission_id' => 442
        ]);

        $Reportes = Module::create(['name' => 'Reportes', 'icon' => 'fas fa-chart-mixed-up-circle-currency']);

        $Reportes->roles()->sync([41]);

        Submodule::create([
            'name' => 'Ventas',
            'url' => '/Dashboard/Reports/Sales/Index',
            'icon' => 'fas fa-hand-holding-dollar',
            'module_id' => $Reportes->id,
            'permission_id' => 455
        ]);

        Submodule::create([
            'name' => 'Despacho',
            'url' => '/Dashboard/Reports/Dispatches/Index',
            'icon' => 'fas fa-hand-holding-box',
            'module_id' => $Reportes->id,
            'permission_id' => 457
        ]);

        Submodule::create([
            'name' => 'Produccion',
            'url' => '/Dashboard/Reports/Productions/Index',
            'icon' => 'fas fa-hand-holding-seedling',
            'module_id' => $Reportes->id,
            'permission_id' => 459
        ]);

        Submodule::create([
            'name' => 'Carteras',
            'url' => '/Dashboard/Reports/Wallets/Index',
            'icon' => 'fas fa-hand-holding-medical',
            'module_id' => $Reportes->id,
            'permission_id' => 461
        ]);
    }
}
