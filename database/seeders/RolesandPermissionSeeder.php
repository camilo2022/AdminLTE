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

        $DocumentTypes = Role::create(['name' => 'DocumentTypes']);

        $Collections = Role::create(['name' => 'Collections']);

        $Packages = Role::create(['name' => 'Packages']);

        $Trademarks = Role::create(['name' => 'Trademarks']);

        $Bussinesses = Role::create(['name' => 'Bussinesses']);

        $Models = Role::create(['name' => 'Models']);

        $ClothingLines = Role::create(['name' => 'ClothingLines']);

        $CategoriesAndSubcategories = Role::create(['name' => 'CategoriesAndSubcategories']);

        $Warehouses = Role::create(['name' => 'Warehouses']);

        $Colors = Role::create(['name' => 'Colors']);

        $Products = Role::create(['name' => 'Products']);

        $Inventories = Role::create(['name' => 'Inventories']);

        $Transfers = Role::create(['name' => 'Transfers']);

        $OrderSeller = Role::create(['name' => 'OrderSeller']);

        $Clients = Role::create(['name' => 'Clients']);

        $Wallet = Role::create(['name' => 'Wallet']);

        $OrderWallet = Role::create(['name' => 'OrderWallet']);

        $OrderDispatch = Role::create(['name' => 'OrderDispatch']);

        $OrderPacked = Role::create(['name' => 'OrderPacked']);

        $OrderInvoiced = Role::create(['name' => 'OrderInvoiced']);

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

        Permission::create(['name' => 'Dashboard.DocumentTypes.Index'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Index.Query'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Create'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Store'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Edit'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Update'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Delete'])->syncRoles([$DocumentTypes]);
        Permission::create(['name' => 'Dashboard.DocumentTypes.Restore'])->syncRoles([$DocumentTypes]);

        Permission::create(['name' => 'Dashboard.Collections.Index'])->syncRoles([$Collections]);
        Permission::create(['name' => 'Dashboard.Collections.Index.Query'])->syncRoles([$Collections]);
        Permission::create(['name' => 'Dashboard.Collections.Create'])->syncRoles([$Collections]);
        Permission::create(['name' => 'Dashboard.Collections.Store'])->syncRoles([$Collections]);
        Permission::create(['name' => 'Dashboard.Collections.Edit'])->syncRoles([$Collections]);
        Permission::create(['name' => 'Dashboard.Collections.Update'])->syncRoles([$Collections]);
        Permission::create(['name' => 'Dashboard.Collections.Delete'])->syncRoles([$Collections]);

        Permission::create(['name' => 'Dashboard.Packages.Index'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Index.Query'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Create'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Store'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Edit'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Update'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Delete'])->syncRoles([$Packages]);
        Permission::create(['name' => 'Dashboard.Packages.Restore'])->syncRoles([$Packages]);

        Permission::create(['name' => 'Dashboard.Trademarks.Index'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Index.Query'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Create'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Store'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Edit'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Update'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Delete'])->syncRoles([$Trademarks]);
        Permission::create(['name' => 'Dashboard.Trademarks.Restore'])->syncRoles([$Trademarks]);

        Permission::create(['name' => 'Dashboard.Businesses.Index'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Index.Query'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Create'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Store'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Edit'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Update'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Delete'])->syncRoles([$Bussinesses]);
        Permission::create(['name' => 'Dashboard.Businesses.Restore'])->syncRoles([$Packages]);

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

        Permission::create(['name' => 'Dashboard.Colors.Index'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Index.Query'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Create'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Store'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Edit'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Update'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Delete'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Restore'])->syncRoles([$Colors]);

        Permission::create(['name' => 'Dashboard.Products.Index'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Index.Query'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Create'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Store'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Edit'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Update'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Show'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Destroy'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Delete'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Restore'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Upload'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Download'])->syncRoles([$Products]);

        Permission::create(['name' => 'Dashboard.Inventories.Index'])->syncRoles([$Inventories]);
        Permission::create(['name' => 'Dashboard.Inventories.Index.Query'])->syncRoles([$Inventories]);
        Permission::create(['name' => 'Dashboard.Inventories.Upload'])->syncRoles([$Inventories]);
        Permission::create(['name' => 'Dashboard.Inventories.Download'])->syncRoles([$Inventories]);

        Permission::create(['name' => 'Dashboard.Transfers.Index'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Index.Query'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Create'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Create'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Store'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Store'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Show'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Edit'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Edit'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Update'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Update'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Delete'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Delete'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Accept'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Accept'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Cancel'])->syncRoles([$Transfers]);
        Permission::create(['name' => 'Dashboard.Transfers.Detail.Cancel'])->syncRoles([$Transfers]);

        Permission::create(['name' => 'Dashboard.Order.Seller.Index'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Index.Query'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Create'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Detail.Create'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Store'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Detail.Store'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Show'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Show.Query'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Edit'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Detail.Edit'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Update'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Detail.Update'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Accept'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Pending'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Detail.Pending'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Cancel'])->syncRoles([$OrderSeller]);
        Permission::create(['name' => 'Dashboard.Order.Seller.Detail.Cancel'])->syncRoles([$OrderSeller]);

        Permission::create(['name' => 'Dashboard.Clients.Index'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Index.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Create'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branch.Create'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Store'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branch.Store'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Show'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Show.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Quota'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Quota.Query'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Edit'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branch.Edit'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Update'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branch.Update'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Delete'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branch.Delete'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Restore'])->syncRoles([$Clients]);
        Permission::create(['name' => 'Dashboard.Clients.Branch.Restore'])->syncRoles([$Clients]);

        Permission::create(['name' => 'Dashboard.Wallet.Index'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Index.Query'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Create'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Store'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Show'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Edit'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Update'])->syncRoles([$Wallet]);
        Permission::create(['name' => 'Dashboard.Wallet.Delete'])->syncRoles([$Wallet]);

        Permission::create(['name' => 'Dashboard.Order.Wallet.Index'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Index.Query'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Create'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Store'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Show'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Show.Query'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Edit'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Update'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Edit'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Update'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Accept'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Accept'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Pending'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Pending'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Cancel'])->syncRoles([$OrderWallet]);
        Permission::create(['name' => 'Dashboard.Order.Wallet.Detail.Cancel'])->syncRoles([$OrderWallet]);

        Permission::create(['name' => 'Dashboard.Order.Dispatch.Index'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Index.Query'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Create'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Store'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Create'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Store'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Show'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Show.Query'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Edit'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Update'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Accept'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Accept'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Pending'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Pending'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Cancel'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Cancel'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Detail.Delete'])->syncRoles([$OrderDispatch]);
        Permission::create(['name' => 'Dashboard.Order.Dispatch.Download'])->syncRoles([$OrderDispatch]);

        Permission::create(['name' => 'Dashboard.Order.Packed.Index'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Index.Query'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Create'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Store'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Package.Create'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Package.Store'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Package.Detail.Store'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Package.Detail.Update'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Package.Edit'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Package.Update'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Accept'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Pending'])->syncRoles([$OrderPacked]);
        Permission::create(['name' => 'Dashboard.Order.Packed.Cancel'])->syncRoles([$OrderPacked]);

        Permission::create(['name' => 'Dashboard.Order.Invoiced.Index'])->syncRoles([$OrderInvoiced]);
        Permission::create(['name' => 'Dashboard.Order.Invoiced.Index.Query'])->syncRoles([$OrderInvoiced]);
        Permission::create(['name' => 'Dashboard.Order.Invoiced.Create'])->syncRoles([$OrderInvoiced]);
        Permission::create(['name' => 'Dashboard.Order.Invoiced.Store'])->syncRoles([$OrderInvoiced]);

        Permission::create(['name' => 'Dashboard.Reports.Sales.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Sales.Index.Query'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Sales.Download'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Dispatches.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Dispatches.Index.Query'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Dispatches.Download'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Productions.Index'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Productions.Index.Query'])->syncRoles([$Reports]);
        Permission::create(['name' => 'Dashboard.Reports.Productions.Download'])->syncRoles([$Reports]);
    }
}
