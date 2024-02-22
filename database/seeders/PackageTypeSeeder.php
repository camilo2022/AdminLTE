<?php

namespace Database\Seeders;

use App\Models\PackageType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PackageType::create(['name' => 'BULTO', 'code' => '01']);
        PackageType::create(['name' => 'CAJA', 'code' => '02']);
        PackageType::create(['name' => 'BOLSA', 'code' => '03']);
    }
}
