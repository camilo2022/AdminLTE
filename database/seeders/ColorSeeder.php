<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create(['name' => 'Sin color', 'code' => '00', 'value' => '#fff']);
        Color::create(['name' => 'Rojo', 'code' => '10', 'value' => '#ff0000']);
        Color::create(['name' => 'Azul', 'code' => '20', 'value' => '#0090ff']);
        Color::create(['name' => 'Morado', 'code' => '30', 'value' => '#6610f2']);
        Color::create(['name' => 'Verde', 'code' => '40', 'value' => '#28a745']);
        Color::create(['name' => 'Naranja', 'code' => '50', 'value' => '#fd7e14']);
    }
}
