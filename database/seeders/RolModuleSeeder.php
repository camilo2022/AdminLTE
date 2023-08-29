<?php

namespace Database\Seeders;

use App\Models\RolModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolModule::create([ 'id_rol' => 1, 'id_module' => 1 ]);

        RolModule::create([ 'id_rol' => 2, 'id_module' => 1 ]);
    }
}
