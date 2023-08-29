<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'name'    => 'SuperAdmin',
            'email'   => 'superadmin@admin.com',
            'password' => bcrypt('12345678'),
            'enterprises_id' => 1,
        ])->assignRole('superadmin');

        User::create([
            'name'    => 'Admin',
            'email'   => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'enterprises_id' => 1,
        ])->assignRole('admin');
    }
}
