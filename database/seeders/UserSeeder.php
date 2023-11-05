<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $RolesAndPermissions = [
            (object) [
                'role' => 'Dashboard',
                'permissions' => [
                    'Dashboard'
                ]
            ],
            (object) [
                'role' => 'Users',
                'permissions' => [
                    'Dashboard.Users.Index',
                    'Dashboard.Users.Index.Query',
                    'Dashboard.Users.Inactives',
                    'Dashboard.Users.Inactives.Query',
                    'Dashboard.Users.Create',
                    'Dashboard.Users.Store',
                    'Dashboard.Users.Edit',
                    'Dashboard.Users.Update',
                    'Dashboard.Users.Show',
                    'Dashboard.Users.Password',
                    'Dashboard.Users.Delete',
                    'Dashboard.Users.Restore',
                    'Dashboard.Users.AssignRoleAndPermissions',
                    'Dashboard.Users.AssignRoleAndPermissions.Query',
                    'Dashboard.Users.RemoveRoleAndPermissions',
                    'Dashboard.Users.RemoveRoleAndPermissions.Query'
                ]
            ],
            (object) [
                'role' => 'RolesAndPermissions',
                'permissions' => [
                    'Dashboard.RolesAndPermissions.Index',
                    'Dashboard.RolesAndPermissions.Index.Query',
                    'Dashboard.RolesAndPermissions.Create',
                    'Dashboard.RolesAndPermissions.Store',
                    'Dashboard.RolesAndPermissions.Edit',
                    'Dashboard.RolesAndPermissions.Update',
                    'Dashboard.RolesAndPermissions.Delete'
                ]
            ],
            (object) [
                'role' => 'ModulesAndSubmodules',
                'permissions' => [
                    'Dashboard.ModulesAndSubmodules.Index',
                    'Dashboard.ModulesAndSubmodules.Index.Query',
                    'Dashboard.ModulesAndSubmodules.Create',
                    'Dashboard.ModulesAndSubmodules.Store',
                    'Dashboard.ModulesAndSubmodules.Edit',
                    'Dashboard.ModulesAndSubmodules.Update',
                    'Dashboard.ModulesAndSubmodules.Delete',
                ]
            ],
            (object) [
                'role' => 'Collections',
                'permissions' => [
                    'Dashboard.Collections.Index',
                    'Dashboard.Collections.Index.Query',
                    'Dashboard.Collections.Create',
                    'Dashboard.Collections.Store',
                    'Dashboard.Collections.Edit',
                    'Dashboard.Collections.Update',
                    'Dashboard.Collections.Delete'
                ]
            ]
        ];

        $user = User::create([
            'name' => 'Camilo Andres',
            'last_name' => 'Acacio Gutierrez',
            'document_number' => '1004845200',
            'phone_number' => '3222759176',
            'address' => 'Cll 11 # 8-32 Panamericano',
            'email' => 'camiloacacio16@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        foreach($RolesAndPermissions as $RoleAndPermission) {
            // Crear o recuperar un permiso con el nombre proporcionado
            $user->assignRole([$RoleAndPermission->role]);
            $user->givePermissionTo($RoleAndPermission->permissions);
        };
    }
}
