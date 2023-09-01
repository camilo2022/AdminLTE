<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesandPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Dashboard = Role::create(['name' => 'Dashboard', 'access_id' => 1]);

        $UserView = Role::create(['name' => 'UserView', 'access_id' => 1]);
        $UserCreate = Role::create(['name' => 'UserCreate', 'access_id' => 2]);
        $UserEdit = Role::create(['name' => 'UserEdit', 'access_id' => 3]);
        $UserDelete = Role::create(['name' => 'UserDelete', 'access_id' => 4]);

        $RoleView = Role::create(['name' => 'RoleView', 'access_id' => 1]);
        $RoleCreate = Role::create(['name' => 'RoleCreate', 'access_id' => 2]);
        $RoleEdit = Role::create(['name' => 'RoleEdit', 'access_id' => 3]);
        $RoleDelete = Role::create(['name' => 'RoleDelete', 'access_id' => 4]);

        $PermissionView = Role::create(['name' => 'PermissionView', 'access_id' => 1]);
        $PermissionCreate = Role::create(['name' => 'PermissionCreate', 'access_id' => 2]);
        $PermissionEdit = Role::create(['name' => 'PermissionEdit', 'access_id' => 3]);
        $PermissionDelete = Role::create(['name' => 'PermissionDelete', 'access_id' => 4]);

        $ModuleView = Role::create(['name' => 'ModuleView', 'access_id' => 1]);
        $ModuleCreate = Role::create(['name' => 'ModuleCreate', 'access_id' => 2]);
        $ModuleEdit = Role::create(['name' => 'ModuleEdit', 'access_id' => 3]);
        $ModuleDelete = Role::create(['name' => 'ModuleDelete', 'access_id' => 4]);

        $SubmoduleView = Role::create(['name' => 'SubmoduleView', 'access_id' => 1]);
        $SubmoduleCreate = Role::create(['name' => 'SubmoduleCreate', 'access_id' => 2]);
        $SubmoduleEdit = Role::create(['name' => 'SubmoduleEdit', 'access_id' => 3]);
        $SubmoduleDelete = Role::create(['name' => 'SubmoduleDelete', 'access_id' => 4]);

        $EnterpriseView = Role::create(['name' => 'EnterpriseView', 'access_id' => 1]);
        $EnterpriseCreate = Role::create(['name' => 'EnterpriseCreate', 'access_id' => 2]);
        $EnterpriseEdit = Role::create(['name' => 'EnterpriseEdit', 'access_id' => 3]);
        $EnterpriseDelete = Role::create(['name' => 'EnterpriseDelete', 'access_id' => 4]);

        Permission::create(['name' => 'Dashboard'])->syncRoles([$Dashboard]);
        Permission::create(['name' => 'Dashboard.User.Index'])->syncRoles([$UserView]);
        Permission::create(['name' => 'Dashboard.User.Create'])->syncRoles([$UserCreate]);
        Permission::create(['name' => 'Dashboard.User.Store'])->syncRoles([$UserCreate]);
        Permission::create(['name' => 'Dashboard.User.Password'])->syncRoles([$UserEdit]);
        Permission::create(['name' => 'Dashboard.User.Edit'])->syncRoles([$UserEdit]);
        Permission::create(['name' => 'Dashboard.User.AssignRole'])->syncRoles([$UserEdit]);
        Permission::create(['name' => 'Dashboard.User.UnassignRole'])->syncRoles([$UserEdit]);
        Permission::create(['name' => 'Dashboard.User.Update'])->syncRoles([$UserEdit]);
        Permission::create(['name' => 'Dashboard.User.Destroy'])->syncRoles([$UserDelete]);
        Permission::create(['name' => 'Dashboard.User.Restore'])->syncRoles([$UserCreate]);
        Permission::create(['name' => 'Dashboard.User.Inactivos'])->syncRoles([$UserView]);

        Permission::create(['name' => 'Dashboard.Rol.Index'])->syncRoles([$RoleView]);
        Permission::create(['name' => 'Dashboard.Rol.Store'])->syncRoles([$RoleCreate]);
        Permission::create(['name' => 'Dashboard.Rol.Show'])->syncRoles([$RoleCreate]);
        Permission::create(['name' => 'Dashboard.Rol.AssignPermission'])->syncRoles([$RoleEdit]);
        Permission::create(['name' => 'Dashboard.Rol.Hide'])->syncRoles([$RoleEdit]);
        Permission::create(['name' => 'Dashboard.Rol.UnssignPermission'])->syncRoles([$RoleEdit]);
        Permission::create(['name' => 'Dashboard.Rol.Edit'])->syncRoles([$RoleEdit]);
        Permission::create(['name' => 'Dashboard.Rol.Update'])->syncRoles([$RoleEdit]);
        Permission::create(['name' => 'Dashboard.Rol.Destroy'])->syncRoles([$RoleDelete]);

        Permission::create(['name' => 'Dashboard.Permission.Index'])->syncRoles([$PermissionView]);
        Permission::create(['name' => 'Dashboard.Permission.Store'])->syncRoles([$PermissionCreate]);
        Permission::create(['name' => 'Dashboard.Permission.Update'])->syncRoles([$PermissionEdit]);
        Permission::create(['name' => 'Dashboard.Permission.Destroy'])->syncRoles([$PermissionDelete]);

        Permission::create(['name' => 'Dashboard.Module.Index'])->syncRoles([$ModuleView]);
        Permission::create(['name' => 'Dashboard.Module.Store'])->syncRoles([$ModuleCreate]);
        Permission::create(['name' => 'Dashboard.Module.Update'])->syncRoles([$ModuleEdit]);
        Permission::create(['name' => 'Dashboard.Module.Destroy'])->syncRoles([$ModuleDelete]);

        Permission::create(['name' => 'Dashboard.SubModule.Index'])->syncRoles([$SubmoduleView]);
        Permission::create(['name' => 'Dashboard.SubModule.Store'])->syncRoles([$SubmoduleCreate]);
        Permission::create(['name' => 'Dashboard.SubModule.Update'])->syncRoles([$SubmoduleEdit]);
        Permission::create(['name' => 'Dashboard.SubModule.Destroy'])->syncRoles([$SubmoduleDelete]);

        Permission::create(['name' => 'Dashboard.Enterprises.Index'])->syncRoles([$EnterpriseView]);
        Permission::create(['name' => 'Dashboard.Enterprises.Store'])->syncRoles([$EnterpriseCreate]);
        Permission::create(['name' => 'Dashboard.Enterprises.Update'])->syncRoles([$EnterpriseEdit]);
        Permission::create(['name' => 'Dashboard.Enterprises.Destroy'])->syncRoles([$EnterpriseDelete]);
    }
}
