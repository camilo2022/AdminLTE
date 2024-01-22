<?php

namespace Database\Seeders;

use App\Models\PersonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PersonType::create(['name' => 'PERSONA NATURAL', 'code' => 'PN', 'require_people' => false]);
        PersonType::create(['name' => 'PERSONA JURIDICA', 'code' => 'PJ', 'require_people' => true]);
    }
}
