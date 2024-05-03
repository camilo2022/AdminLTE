<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Dashboard = Role::create(['name' => 'Dashboard']);

        $Users = Role::create(['name' => 'Users']);

        $RolesAndPermissions = Role::create(['name' => 'RolesAndPermissions']);

        $ModulesAndSubmodules = Role::create(['name' => 'ModulesAndSubmodules']);

        $AreasAndCharges = Role::create(['name' => 'AreasAndCharges']);

        $DocumentTypes = Role::create(['name' => 'DocumentTypes']);

        $ClientTypes = Role::create(['name' => 'ClientTypes']);

        $PersonTypes = Role::create(['name' => 'PersonTypes']);

        $PackageTypes = Role::create(['name' => 'PackageTypes']);

        $ReturnTypes = Role::create(['name' => 'ReturnTypes']);

        $Transporters = Role::create(['name' => 'Transporters']);

        $Banks = Role::create(['name' => 'Banks']);

        $PaymentTypes = Role::create(['name' => 'PaymentTypes']);

        $Bussinesses = Role::create(['name' => 'Bussinesses']);

        $Warehouses = Role::create(['name' => 'Warehouses']);

        $CorreriasAndCollections = Role::create(['name' => 'CorreriasAndCollections']);

        $SaleChannels = Role::create(['name' => 'SaleChannels']);

        $Workshops = Role::create(['name' => 'Workshops']);

        $Suppliers = Role::create(['name' => 'Suppliers']);

        $SupplyTypes = Role::create(['name' => 'SupplyTypes']);

        $ClothTypes = Role::create(['name' => 'ClothTypes']);

        $ClothCompositions = Role::create(['name' => 'ClothCompositions']);

        $Supplies = Role::create(['name' => 'Supplies']);

        $Sizes = Role::create(['name' => 'Sizes']);

        $Trademarks = Role::create(['name' => 'Trademarks']);

        $Models = Role::create(['name' => 'Models']);

        $ClothingLines = Role::create(['name' => 'ClothingLines']);

        $CategoriesAndSubcategories = Role::create(['name' => 'CategoriesAndSubcategories']);

        $Colors = Role::create(['name' => 'Colors']);

        $Tones = Role::create(['name' => 'Tones']);

        $Products = Role::create(['name' => 'Products']);

        $OrderPurchases = Role::create(['name' => 'OrderPurchases']);

        $Inventories = Role::create(['name' => 'Inventories']);

        $Transfers = Role::create(['name' => 'Transfers']);

        $OrderSellers = Role::create(['name' => 'OrderSellers']);

        $Clients = Role::create(['name' => 'Clients']);

        $Wallets = Role::create(['name' => 'Wallets']);

        $OrderWallets = Role::create(['name' => 'OrderWallets']);

        $OrderDispatches = Role::create(['name' => 'OrderDispatches']);

        $OrderPackings = Role::create(['name' => 'OrderPackings']);

        $OrderInvoices = Role::create(['name' => 'OrderInvoices']);

        $OrderReturns = Role::create(['name' => 'OrderReturns']);

        $Reports = Role::create(['name' => 'Reports']);

        Permission::create(['name' => 'Dashboard'])->syncRoles([$Dashboard]);

        Permission::create(['name' => 'Dashboard.Users.Index'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Index.Query'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Inactives'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Inactives.Query'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Create'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Store'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Edit'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Update'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Show'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Password'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Delete'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.Restore'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.AssignRoleAndPermissions'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.AssignRoleAndPermissions.Query'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.RemoveRoleAndPermissions'])->syncRoles([$Users]);
        Permission::create(['name' => 'Dashboard.Users.RemoveRoleAndPermissions.Query'])->syncRoles([$Users]);

        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Index'])->syncRoles([$RolesAndPermissions]);
        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Index.Query'])->syncRoles([$RolesAndPermissions]);
        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Create'])->syncRoles([$RolesAndPermissions]);
        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Store'])->syncRoles([$RolesAndPermissions]);
        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Edit'])->syncRoles([$RolesAndPermissions]);
        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Update'])->syncRoles([$RolesAndPermissions]);
        Permission::create(['name' => 'Dashboard.RolesAndPermissions.Delete'])->syncRoles([$RolesAndPermissions]);

        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Index'])->syncRoles([$ModulesAndSubmodules]);
        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Index.Query'])->syncRoles([$ModulesAndSubmodules]);
        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Create'])->syncRoles([$ModulesAndSubmodules]);
        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Store'])->syncRoles([$ModulesAndSubmodules]);
        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Edit'])->syncRoles([$ModulesAndSubmodules]);
        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Update'])->syncRoles([$ModulesAndSubmodules]);
        Permission::create(['name' => 'Dashboard.ModulesAndSubmodules.Delete'])->syncRoles([$ModulesAndSubmodules]);

        Permission::create(['name' => 'Dashboard.AreasAndCharges.Index'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Index.Query'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Create'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Store'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Edit'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Update'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Delete'])->syncRoles([$AreasAndCharges]);
        Permission::create(['name' => 'Dashboard.AreasAndCharges.Restore'])->syncRoles([$AreasAndCharges]);

        Permission::create(['name' => 'Dashboard.DocumentTypes.Index'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Index.Query'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Create'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Store'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Edit'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Update'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Delete'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Restore'])->syncRoles([$DocumentTypes]);

        Permission::create(['name' => 'Dashboard.ClientTypes.Index'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Index.Query'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Create'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Store'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Edit'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Update'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Delete'])->syncRoles([$ClientTypes]);
        Permission::create(['name' => 'Dashboard.ClientTypes.Restore'])->syncRoles([$ClientTypes]);

        Permission::create(['name' => 'Dashboard.PersonTypes.Index'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Index.Query'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Create'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Store'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Edit'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Update'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Show'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.AssignDocumentType'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.RemoveDocumentType'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Delete'])->syncRoles([$PersonTypes]);
        Permission::create(['name' => 'Dashboard.PersonTypes.Restore'])->syncRoles([$PersonTypes]);

        Permission::create(['name' => 'Dashboard.PackageTypes.Index'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Index.Query'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Create'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Store'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Edit'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Update'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Delete'])->syncRoles([$PackageTypes]);
        Permission::create(['name' => 'Dashboard.PackageTypes.Restore'])->syncRoles([$PackageTypes]);

        Permission::create(['name' => 'Dashboard.ReturnTypes.Index'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Index.Query'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Create'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Store'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Edit'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Update'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Delete'])->syncRoles([$ReturnTypes]);
        Permission::create(['name' => 'Dashboard.ReturnTypes.Restore'])->syncRoles([$ReturnTypes]);

        Permission::create(['name' => 'Dashboard.Transporters.Index'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Index.Query'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Create'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Store'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Edit'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Update'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Delete'])->syncRoles([$Transporters]);
        Permission::create(['name' => 'Dashboard.Transporters.Restore'])->syncRoles([$Transporters]);

        Permission::create(['name' => 'Dashboard.Banks.Index'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Index.Query'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Create'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Store'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Edit'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Update'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Delete'])->syncRoles([$Banks]);
        Permission::create(['name' => 'Dashboard.Banks.Restore'])->syncRoles([$Banks]);

        Permission::create(['name' => 'Dashboard.PaymentTypes.Index'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Index.Query'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Create'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Store'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Edit'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Update'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Delete'])->syncRoles([$PaymentTypes]);
        Permission::create(['name' => 'Dashboard.PaymentTypes.Restore'])->syncRoles([$PaymentTypes]);

        Permission::create(['name' => 'Dashboard.Businesses.Index'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Index.Query'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Create'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Store'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Edit'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Update'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Delete'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Restore'])->syncRoles([$Bussinesses]);

        Permission::create(['name' => 'Dashboard.Warehouses.Index'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Index.Query'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Create'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Store'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Edit'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Update'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Show'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.AssignGestor'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.RemoveGestor'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Delete'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Restore'])->syncRoles([$Warehouses]);

        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Index'])->syncRoles([$CorreriasAndCollections]);
        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Index.Query'])->syncRoles([$CorreriasAndCollections]);
        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Create'])->syncRoles([$CorreriasAndCollections]);
        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Store'])->syncRoles([$CorreriasAndCollections]);
        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Edit'])->syncRoles([$CorreriasAndCollections]);
        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Update'])->syncRoles([$CorreriasAndCollections]);
        Permission::create(['name' => 'Dashboard.CorreriasAndCollections.Delete'])->syncRoles([$CorreriasAndCollections]);

        Permission::create(['name' => 'Dashboard.SaleChannels.Index'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Index.Query'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Create'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Store'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Edit'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Update'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Show'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.AssignReturnType'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.RemoveReturnType'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Delete'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.SaleChannels.Restore'])->syncRoles([$SaleChannels]);

        Permission::create(['name' => 'Dashboard.Workshops.Index'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Index.Query'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Create'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Store'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Edit'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Update'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Show'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.AssignAccount'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.RemoveAccount'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.Workshops.AssignProcess'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.RemoveProcess'])->syncRoles([$SaleChannels]);
        Permission::create(['name' => 'Dashboard.Workshops.Delete'])->syncRoles([$Workshops]);
        Permission::create(['name' => 'Dashboard.Workshops.Restore'])->syncRoles([$Workshops]);

        Permission::create(['name' => 'Dashboard.Suppliers.Index'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Index.Query'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Create'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Store'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Edit'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Update'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Show'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Delete'])->syncRoles([$Suppliers]);
        Permission::create(['name' => 'Dashboard.Suppliers.Restore'])->syncRoles([$Suppliers]);

        Permission::create(['name' => 'Dashboard.SupplyTypes.Index'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Index.Query'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Create'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Store'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Edit'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Update'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Delete'])->syncRoles([$SupplyTypes]);
        Permission::create(['name' => 'Dashboard.SupplyTypes.Restore'])->syncRoles([$SupplyTypes]);

        Permission::create(['name' => 'Dashboard.ClothTypes.Index'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Index.Query'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Create'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Store'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Edit'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Update'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Delete'])->syncRoles([$ClothTypes]);
        Permission::create(['name' => 'Dashboard.ClothTypes.Restore'])->syncRoles([$ClothTypes]);

        Permission::create(['name' => 'Dashboard.ClothCompositions.Index'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Index.Query'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Create'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Store'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Edit'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Update'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Delete'])->syncRoles([$ClothCompositions]);
        Permission::create(['name' => 'Dashboard.ClothCompositions.Restore'])->syncRoles([$ClothCompositions]);

        Permission::create(['name' => 'Dashboard.Supplies.Index'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Index.Query'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Create'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Store'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Edit'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Update'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Show'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Charge'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Destroy'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Delete'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Restore'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Upload'])->syncRoles([$Supplies]);
        Permission::create(['name' => 'Dashboard.Supplies.Download'])->syncRoles([$Supplies]);

        Permission::create(['name' => 'Dashboard.Sizes.Index'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Index.Query'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Create'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Store'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Edit'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Update'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Delete'])->syncRoles([$Sizes]);
        Permission::create(['name' => 'Dashboard.Sizes.Restore'])->syncRoles([$Sizes]);

        Permission::create(['name' => 'Dashboard.Trademarks.Index'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Index.Query'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Create'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Store'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Edit'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Update'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Delete'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Restore'])->syncRoles([$Trademarks]);

        Permission::create(['name' => 'Dashboard.Models.Index'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Index.Query'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Create'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Store'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Edit'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Update'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Delete'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Restore'])->syncRoles([$Models]);

        Permission::create(['name' => 'Dashboard.ClothingLines.Index'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Index.Query'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Create'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Store'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Edit'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Update'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Delete'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Restore'])->syncRoles([$ClothingLines]);

        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Index'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Index.Query'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Create'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Store'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Edit'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Update'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Delete'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Restore'])->syncRoles([$CategoriesAndSubcategories]);

        Permission::create(['name' => 'Dashboard.Colors.Index'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Index.Query'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Create'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Store'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Edit'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Update'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Delete'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Restore'])->syncRoles([$Colors]);

        Permission::create(['name' => 'Dashboard.Tones.Index'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Index.Query'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Create'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Store'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Edit'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Update'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Delete'])->syncRoles([$Tones]);
        Permission::create(['name' => 'Dashboard.Tones.Restore'])->syncRoles([$Tones]);

        Permission::create(['name' => 'Dashboard.Products.Index'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Index.Query'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Create'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Store'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Edit'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Update'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Show'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.AssignSize'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.RemoveSize'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.AssignColorTone'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.RemoveColorTone'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Charge'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Destroy'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Delete'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Restore'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Upload'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Download'])->syncRoles([$Products]);

        Permission::create(['name' => 'Dashboard.Orders.Purchase.Index'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Index.Query'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Create'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Store'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Edit'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Update'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Approve'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Pending'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Cancel'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Receive'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Payments.Query'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.AssignPayment.Query'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.AssignPayment'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.RemovePayment'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.ApprovePayment'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.CancelPayment'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Download'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Index'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Index.Query'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Create'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Store'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Edit'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Update'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Pending'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Request.Cancel'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Receive.Index'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Receive.Index.Query'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Receive.Create'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Receive.Store'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Receive.Edit'])->syncRoles([$OrderPurchases]);
        Permission::create(['name' => 'Dashboard.Orders.Purchase.Details.Receive.Update'])->syncRoles([$OrderPurchases]);

        Permission::create(['name' => 'Dashboard.Inventories.Index'])->syncRoles([$Inventories]);
        Permission::create(['name' => 'Dashboard.Inventories.Index.Query'])->syncRoles([$Inventories]);
        Permission::create(['name' => 'Dashboard.Inventories.Upload'])->syncRoles([$Inventories]);
        Permission::create(['name' => 'Dashboard.Inventories.Download'])->syncRoles([$Inventories]);

        Permission::create(['name' => 'Dashboard.Transfers.Index'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Index.Query'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Create'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Store'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Show'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Edit'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Update'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Delete'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Approve'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Cancel'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Index.Query'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Create'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Store'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Edit'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Update'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Delete'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Pending'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Details.Cancel'])->syncRoles([$Transfers]);

        Permission::create(['name' => 'Dashboard.Orders.Seller.Index'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Index.Query'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Create'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Store'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Edit'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Update'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Approve'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Pending'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Cancel'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Payments.Query'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.AssignPayment.Query'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.AssignPayment'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.RemovePayment'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.ApprovePayment'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.CancelPayment'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Email'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Download'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Index'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Index.Query'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Create'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Store'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Edit'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Update'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Pending'])->syncRoles([$OrderSellers]);
        Permission::create(['name' => 'Dashboard.Orders.Seller.Details.Cancel'])->syncRoles([$OrderSellers]);

        Permission::create(['name' => 'Dashboard.Clients.Index'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Index.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Create'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Store'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Edit'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Update'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Show'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Show.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Quota'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Quota.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Delete'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Restore'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Index'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Index.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Create'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Store'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Edit'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Update'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Delete'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branches.Restore'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.Create'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.Store'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.Edit'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.Update'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Index'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Index.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Create'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Store'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Edit'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Update'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Delete'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.People.References.Restore'])->syncRoles([$Clients]);

        Permission::create(['name' => 'Dashboard.Wallets.Index'])->syncRoles([$Wallets]);
        Permission::create(['name' => 'Dashboard.Wallets.Index.Query'])->syncRoles([$Wallets]);
        Permission::create(['name' => 'Dashboard.Wallets.AssignPayment.Query'])->syncRoles([$Wallets]);
        Permission::create(['name' => 'Dashboard.Wallets.AssignPayment'])->syncRoles([$Wallets]);

        Permission::create(['name' => 'Dashboard.Orders.Wallet.Index'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Index.Query'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Observation'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Approve'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.PartiallyApprove'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Pending'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Cancel'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Payments.Query'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Index'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Index.Query'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Create'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Store'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Edit'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Update'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Approve'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Pending'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Review'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Cancel'])->syncRoles([$OrderWallets]);
        Permission::create(['name' => 'Dashboard.Orders.Wallet.Details.Decline'])->syncRoles([$OrderWallets]);

        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Index'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Index.Query'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Filter'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Filter.Query.Details'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Filter.Query.Inventories'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Store'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Pending'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Approve'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Cancel'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Decline'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Show'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Download'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Details.Index'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Details.Index.Query'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Details.Approve'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Details.Pending'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Details.Cancel'])->syncRoles([$OrderDispatches]);
        Permission::create(['name' => 'Dashboard.Orders.Dispatch.Details.Decline'])->syncRoles([$OrderDispatches]);

        Permission::create(['name' => 'Dashboard.Orders.Packed.Index'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Index.Query'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Store'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Finish'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Delete'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Index'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Index.Query'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Store'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Detail'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Show'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Show.Query'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Open'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Close'])->syncRoles([$OrderPackings]);
        Permission::create(['name' => 'Dashboard.Orders.Packed.Package.Delete'])->syncRoles([$OrderPackings]);

        Permission::create(['name' => 'Dashboard.Orders.Invoice.Index'])->syncRoles([$OrderInvoices]);
        Permission::create(['name' => 'Dashboard.Orders.Invoice.Index.Query'])->syncRoles([$OrderInvoices]);
        Permission::create(['name' => 'Dashboard.Orders.Invoice.Create'])->syncRoles([$OrderInvoices]);
        Permission::create(['name' => 'Dashboard.Orders.Invoice.Store'])->syncRoles([$OrderInvoices]);
        Permission::create(['name' => 'Dashboard.Orders.Invoice.Download'])->syncRoles([$OrderInvoices]);

        Permission::create(['name' => 'Dashboard.Orders.Return.Index'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Index.Query'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Create'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Store'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Approve'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Cancel'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Index'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Index.Query'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Create'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Store'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Edit'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Update'])->syncRoles([$OrderReturns]);
        Permission::create(['name' => 'Dashboard.Orders.Return.Details.Cancel'])->syncRoles([$OrderReturns]);

        Permission::create(['name' => 'Dashboard.Reports.Sales.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Sales.Index.Query'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Dispatches.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Dispatches.Index.Query'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Productions.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Productions.Index.Query'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Wallets.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Wallets.Index.Query'])->syncRoles([$Reports]);
    }
}
