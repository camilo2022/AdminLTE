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
            'name' => 'Director General',
            'description' => 'Responsable de la toma de decisiones estratégicas para toda la organización.'
        ]);

        Charge::create([
            'area_id' => 1,
            'name' => 'Gerente de Proyectos',
            'description' => 'Encargado de la planificación y ejecución de proyectos dentro de la organización.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Analista de Crédito',
            'description' => 'Evaluación de la solvencia crediticia de clientes y emisión de límites de crédito.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Gestor de Cobranza',
            'description' => 'Encargado de realizar el seguimiento y la gestión de cuentas por cobrar.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Especialista en Facturación',
            'description' => 'Responsable de la emisión de facturas y la documentación asociada.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Coordinador de Cartera',
            'description' => 'Supervisión y coordinación de las actividades de gestión de cartera.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Analista de Riesgos',
            'description' => 'Evaluación y gestión de los riesgos asociados a las cuentas por cobrar'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Especialista en Conciliación',
            'description' => 'Encargado de conciliar pagos y resolver discrepancias en cuentas.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Asesor Financiero',
            'description' => 'Proporciona asesoramiento financiero a clientes en relación con sus cuentas.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Especialista en Recuperación de Deudas',
            'description' => 'Se encarga de la recuperación de cuentas atrasadas o en mora.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Analista de Cartera',
            'description' => 'Realiza análisis y reportes relacionados con el estado de la cartera.'
        ]);

        Charge::create([
            'area_id' => 2,
            'name' => 'Coordinador de Facturación y Cobranza',
            'description' => 'Supervisa tanto el proceso de facturación como las actividades de cobranza.'
        ]);
    }
}
