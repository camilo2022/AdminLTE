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
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);

        Permission::create(['name' => '/home'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Index'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Create'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Store'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Password'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Edit'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Update'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Show.Module'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Assign_module'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Hide.Module'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Unssign_module'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Show.SubModule'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Show.SubModule.allsubmodule'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Assign_submodule'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Hide.SubModule'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Hide.SubModule.allsubmodule'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Unssign_submodule'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Destroy'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Restore'])->syncRoles([$superadmin, $admin]);
        Permission::create(['name' => 'Dashboard.User.Inactivos'])->syncRoles([$superadmin, $admin]);

        Permission::create(['name' => 'Dashboard.Rol.Index'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Store'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Show'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Assign_permission'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Hide'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Unssign_permission'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Edit'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Update'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Rol.Destroy'])->syncRoles([$superadmin]);

        Permission::create(['name' => 'Dashboard.Permission.Index'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Permission.Store'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Permission.Update'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Permission.Destroy'])->syncRoles([$superadmin]);

        Permission::create(['name' => 'Dashboard.Module.Index'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Store'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Update'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Destroy'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Show'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Assign_rol'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Hide'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Module.Unsign_rol'])->syncRoles([$superadmin]);

        Permission::create(['name' => 'Dashboard.SubModule.Index'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Store'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Update'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Destroy'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Show'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Assign_rol'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Hide'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.SubModule.Unsign_rol'])->syncRoles([$superadmin]);

        Permission::create(['name' => 'Dashboard.Enterprises.Index'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Store'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Update'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Destroy'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Show.Users'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Assign_users'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Hide.Users'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Unssign_users'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Show.Modules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Assign_modules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Hide.Modules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Unssign_modules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Show.SubModules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Show.SubModule.allsubmodules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Assign_submodules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Hide.SubModules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Hide.SubModule.allsubmodules'])->syncRoles([$superadmin]);
        Permission::create(['name' => 'Dashboard.Enterprises.Unssign_submodules'])->syncRoles([$superadmin]);
    }
}
