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

        $Collections = Role::create(['name' => 'Collections']);

        $Packages = Role::create(['name' => 'Packages']);

        $Warehouses = Role::create(['name' => 'Warehouses']);

        $Colors = Role::create(['name' => 'Colors']);

        $Models = Role::create(['name' => 'Models']);

        $ClothingLines = Role::create(['name' => 'ClothingLines']);

        $CategoriesAndSubcategories = Role::create(['name' => 'CategoriesAndSubcategories']);

        $Products = Role::create(['name' => 'Products']);

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
        
        Permission::create(['name' => 'Dashboard.Warehouses.Index'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Index.Query'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Create'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Store'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Edit'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Update'])->syncRoles([$Warehouses]);
        Permission::create(['name' => 'Dashboard.Warehouses.Delete'])->syncRoles([$Warehouses]);

        Permission::create(['name' => 'Dashboard.Colors.Index'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Index.Query'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Create'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Store'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Edit'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Update'])->syncRoles([$Colors]);
        Permission::create(['name' => 'Dashboard.Colors.Delete'])->syncRoles([$Colors]);

        Permission::create(['name' => 'Dashboard.Models.Index'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Index.Query'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Create'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Store'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Edit'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Update'])->syncRoles([$Models]);
        Permission::create(['name' => 'Dashboard.Models.Delete'])->syncRoles([$Models]);

        Permission::create(['name' => 'Dashboard.ClothingLines.Index'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Index.Query'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Create'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Store'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Edit'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Update'])->syncRoles([$ClothingLines]);
        Permission::create(['name' => 'Dashboard.ClothingLines.Delete'])->syncRoles([$ClothingLines]);

        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Index'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Index.Query'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Create'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Store'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Edit'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Update'])->syncRoles([$CategoriesAndSubcategories]);
        Permission::create(['name' => 'Dashboard.CategoriesAndSubcategories.Delete'])->syncRoles([$CategoriesAndSubcategories]);

        Permission::create(['name' => 'Dashboard.Products.Index'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Index.Query'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Create'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Store'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Edit'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Update'])->syncRoles([$Products]);
        Permission::create(['name' => 'Dashboard.Products.Delete'])->syncRoles([$Products]);

    }
}
