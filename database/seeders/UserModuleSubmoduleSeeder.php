<?php

namespace Database\Seeders;

use App\Models\UserModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserModuleSubmoduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserModule::create([
            "user_id" => 1,
            "module_id" => 1,
            "sub_modules" => "[1,2,3,4,5,6,7]"
        ]);
        UserModule::create([
            "user_id" => 2,
            "module_id" => 1,
            "sub_modules" => "[1,2]"
        ]);
    }
}
