<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

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
                    'Dashboard.Users.Store',
                    'Dashboard.Users.Update',
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
                    'Dashboard.RolesAndPermissions.Roles.Query',
                    'Dashboard.RolesAndPermissions.Permissions.Query',
                    'Dashboard.RolesAndPermissions.Store',
                    'Dashboard.RolesAndPermissions.Update',
                    'Dashboard.RolesAndPermissions.Delete',
                ]
            ],
            (object) [
                'role' => 'ModulesAndSubmodules',
                'permissions' => [
                    'Dashboard.ModulesAndSubmodules.Index',
                    'Dashboard.ModulesAndSubmodules.Index.Query',
                    'Dashboard.ModulesAndSubmodules.Store',
                    'Dashboard.ModulesAndSubmodules.Update',
                    'Dashboard.ModulesAndSubmodules.Delete',
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
            'password' => bcrypt('12345678'),
            'enterprise_id' => 1,
        ]);

        foreach($RolesAndPermissions as $RoleAndPermission) {
            // Crear o recuperar un permiso con el nombre proporcionado
            $user->assignRole([$RoleAndPermission->role]);
            $user->givePermissionTo($RoleAndPermission->permissions);
        };

        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            User::create([
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'document_number' => $faker->unique()->numberBetween(1000000000, 9999999999),
                'phone_number' => $faker->numberBetween(3000000000, 3999999999),
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('12345678'),
                'enterprise_id' => 1,
            ]);
        }
    }
}
