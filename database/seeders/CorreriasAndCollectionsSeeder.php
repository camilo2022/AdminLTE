<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Correria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CorreriasAndCollectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Correria::create([
            'name' => 'INICIO DE AÑO 2024',
            'code' => 'C12024',
            'start_date' => '2024-02-07',
            'end_date' => '2024-03-11'
        ]);
        
        Correria::create([
            'name' => 'MADRES 2024',
            'code' => 'C22024',
            'start_date' => '2024-03-21',
            'end_date' => '2024-04-22'
        ]);
        
        Correria::create([
            'name' => 'PADRES - VACACIONES 2024',
            'code' => 'C32024',
            'start_date' => '2024-05-15',
            'end_date' => '2024-06-10'
        ]);
        
        Correria::create([
            'name' => 'COLOMBIA MODA 23 - 24 - 25 JULIO 2024',
            'code' => 'C42024',
            'start_date' => '2024-07-27',
            'end_date' => '2024-08-26'
        ]);
        
        Correria::create([
            'name' => 'FIN DE AÑO 2024',
            'code' => 'C52024',
            'start_date' => '2024-09-04',
            'end_date' => '2024-10-07'
        ]);

        Collection::create([
            'correria_id' => 1,
            'date_definition_start_pilots' => '2023-11-15',
            'date_definition_start_samples' => '2024-01-15',
            'proyection_stop_warehouse' => 30,
            'number_samples_include_suitcase' => 40
        ]);
        
        Collection::create([
            'correria_id' => 2,
            'date_definition_start_pilots' => '2024-01-28',
            'date_definition_start_samples' => '2024-03-15',
            'proyection_stop_warehouse' => 30,
            'number_samples_include_suitcase' => 60
        ]);
        
        Collection::create([
            'correria_id' => 3,
            'date_definition_start_pilots' => '2024-03-30',
            'date_definition_start_samples' => '2024-05-08',
            'proyection_stop_warehouse' => 30,
            'number_samples_include_suitcase' => 40
        ]);
        
        Collection::create([
            'correria_id' => 4,
            'date_definition_start_pilots' => '2024-05-20',
            'date_definition_start_samples' => '2024-07-21',
            'proyection_stop_warehouse' => 50,
            'number_samples_include_suitcase' => 70
        ]);
        
        Collection::create([
            'correria_id' => 5,
            'date_definition_start_pilots' => '2024-07-29',
            'date_definition_start_samples' => '2024-08-30',
            'proyection_stop_warehouse' => 15,
            'number_samples_include_suitcase' => 70
        ]);
    }
}
