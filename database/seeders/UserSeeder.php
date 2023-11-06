<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $RolesAndPermissions = Role::with('permissions')->get();

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
            $user->assignRole([$RoleAndPermission->name]);
            $user->givePermissionTo($RoleAndPermission->permissions->pluck('name'));
        };
    }
}
