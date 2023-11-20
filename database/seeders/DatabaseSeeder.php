<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreaSeeder::class);
        $this->call(ChargeSeeder::class);
        $this->call(RolesAndPermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ModulesAndSubmodulesSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(DepartamentSeeder::class);
        $this->call(CitySeeder::class);
    }
}
