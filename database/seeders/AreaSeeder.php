<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create(['name' => 'ADMINISTRACION']);

        Area::create(['name' => 'COMERCIAL']);

        Area::create(['name' => 'BODEGA']);

        Area::create(['name' => 'DISEÑO']);

        Area::create(['name' => 'SISTEMAS']);
    }
}
