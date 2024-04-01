<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Charge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreasAndChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create(['name' => 'Administración']);

        Charge::create([
            'area_id' => 1,
            'name' => 'Administrador',
            'description' => ''
        ]);

        Charge::create([
            'area_id' => 1,
            'name' => 'Gerente Administrativo',
            'description' => ''
        ]);

        Charge::create([
            'area_id' => 1,
            'name' => 'Asistente Administrativo',
            'description' => ''
        ]);

        Charge::create([
            'area_id' => 1,
            'name' => 'Coordinador Administrativo',
            'description' => ''
        ]);

        Area::create(['name' => 'Contabilidad']);

        Charge::create([
            'area_id' => 2,
            'name' => 'Contador',
            'description' => ''
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Analista Contable',
            'description' => ''
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Auditor Interno',
            'description' => ''
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Auxiliar Contable',
            'description' => ''
        ]);

        Area::create(['name' => 'Cartera']);
        
        Charge::create([
            'area_id' => 3,
            'name' => 'Analista de Cartera',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 3,
            'name' => 'Gestor de Cobranzas',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 3,
            'name' => 'Analista de Crédito',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 3,
            'name' => 'Supervisor de Cartera',
            'description' => ''
        ]);

        Area::create(['name' => 'Venta Asistida']);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'Representante de Ventas',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'Asesor Comercial',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'Ejecutivo de Ventas',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 4,
            'name' => 'Coordinador de Ventas',
            'description' => ''
        ]);

        Area::create(['name' => 'Bodega']);
        
        Charge::create([
            'area_id' => 5,
            'name' => 'Jefe de Almacén',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 5,
            'name' => 'Auxiliar de Bodega',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 5,
            'name' => 'Coordinador de Logística',
            'description' => ''
        ]);
        
        Charge::create([
            'area_id' => 5,
            'name' => 'Operario de Almacén',
            'description' => ''
        ]);
    }
}
