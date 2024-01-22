<?php

namespace Database\Seeders;

use App\Models\ReturnType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReturnTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReturnType::create(['name' => 'CLIENTE SE REHUSA A RECIBIR']);
        ReturnType::create(['name' => 'SE GASTO EL DINERO']);
        ReturnType::create(['name' => 'SALIO DE SU RESIDENCIA Y NO DEJO EL DINERO']);
        ReturnType::create(['name' => 'PIDE QUE SE LO LLEVEN EN UNA FECHA DEMASIADO LARGA PARA VOLVER A OFRECER']);
        ReturnType::create(['name' => 'SOLO QUIERE UNA DE LAS PRENDAS MAS NO EL PACK COMPLETO']);
    }
}
