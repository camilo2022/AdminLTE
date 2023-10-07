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
            'name' => 'Camilo Andres',
            'last_name' => 'Acacio Gutierrez',
            'document_number' => '1004845200',
            'phone_number' => '3222759176',
            'address' => 'Cll 11 # 8-32 Panamericano',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'enterprise_id' => 1,
        ]);
    }
}
