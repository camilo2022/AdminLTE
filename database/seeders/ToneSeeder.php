<?php

namespace Database\Seeders;

use App\Models\Tone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tone::create(['name' => 'CLARO']);
        Tone::create(['name' => 'MEDIO']);
        Tone::create(['name' => 'OSCURO']);
    }
}
