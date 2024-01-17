<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Charge::create([
            'area_id' => 1,
            'name' => 'GERENCIA',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 1,
            'name' => 'DIRECION ADMINISTRATIVA Y FINANCIERA',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 1,
            'name' => 'CONTADOR',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 1,
            'name' => 'AUXILIAR CONTABLE',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 1,
            'name' => 'AUXILIAR CARTERA',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'DIRECION COMERCIAL VENTA ASISTIDA',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'Community manager - Trafficker  Digital',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'Gestora Telefónica - Novedades',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'ASESOR COMERCIAL',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'DIRECCION COMERCIAL VENTA NACIONAL',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'COMISIONISTAS NACIONALES',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'DIRECION COMERCIAL CALZADO',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'VENDEDOR NACIONAL',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 2,
            'name' => 'TALLERES SATELITE',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 3,
            'name' => 'JEFE BODEGA',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 3,
            'name' => 'AUXILIAR BODEGA',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'DISEÑADOR Y JEFE DE PRODUCCION',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'PERSONAL DE CORTE',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'PERSONAL DE TERMINACION',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'LAVANDERIAS',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'TALLER SATELITES',
            'description' => ''
        ]);
    }
}
