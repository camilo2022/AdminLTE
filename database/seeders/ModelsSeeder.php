<?php

namespace Database\Seeders;

use App\Models\Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::create(['name' => 'BOTA CAMPANA', 'code' => '01', 'description' => 'JEANS DAMA']);
        Model::create(['name' => 'BOTA RECTA', 'code' => '02', 'description' => 'JEANS DAMA']);
        Model::create(['name' => 'SKINNY', 'code' => '03', 'description' => 'JEANS DAMA']);
        Model::create(['name' => 'BOYNFRIEND', 'code' => '04', 'description' => 'JEANS DAMA']);
        Model::create(['name' => 'MOM', 'code' => '05', 'description' => 'JEANS DAMA']);
        Model::create(['name' => 'SHORTS', 'code' => '06', 'description' => 'SHORTS DAMA']);
        Model::create(['name' => 'FALDA', 'code' => '07', 'description' => 'FALDA DAMA']);
        Model::create(['name' => 'PLATAFORMA', 'code' => '08', 'description' => 'PLATAFORMA DAMA']);
        Model::create(['name' => 'DEPORTIVOS', 'code' => '09', 'description' => 'DEPORTIVO DAMA']);
        Model::create(['name' => 'ESPADRILAS', 'code' => '10', 'description' => 'ESPADRILA DAMA']);
        Model::create(['name' => 'SANDALIA', 'code' => '11', 'description' => 'SANDALIA DAMA']);
        Model::create(['name' => 'TACON', 'code' => '12', 'description' => 'CALZADO DAMA']);
        
    }
}
