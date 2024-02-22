<?php

namespace Database\Seeders;

use App\Models\ClothingLine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClothingLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClothingLine::create(['name' => 'DAMA', 'code' => '01', 'description' => 'DAMA']);
        ClothingLine::create(['name' => 'CABALLERO', 'code' => '02', 'description' => 'CABALLERO']);
        ClothingLine::create(['name' => 'NIÑO', 'code' => '03', 'description' => 'NIÑO']);
        ClothingLine::create(['name' => 'NIÑA', 'code' => '04', 'description' => 'NIÑA']);
    }
}
