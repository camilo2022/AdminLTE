<?php

namespace Database\Seeders;

use App\Models\Access;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Access::create([
            'name' => 'Visualizar',
        ]);

        Access::create([
            'name' => 'Crear',
        ]);

        Access::create([
            'name' => 'Editar',
        ]);

        Access::create([
            'name' => 'Eliminar',
        ]);
    }
}
