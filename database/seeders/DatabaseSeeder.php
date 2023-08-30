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
        $this->call(EnterpriseSeeder::class);
        $this->call(RolesandPermissionSeeder::class);
        $this->call(ModulesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SubModuleSeeder::class);
        $this->call(UserModuleSubmoduleSeeder::class);
        $this->call(RolModuleSeeder::class);
        $this->call(UserEnterpriseSeeder::class);
        $this->call(ModuleEnterpriseSeeder::class);
        $this->call(SubModuleEnterpriseSeeder::class);
        $this->call(RolSubModulesSeeder::class);
    }
}
