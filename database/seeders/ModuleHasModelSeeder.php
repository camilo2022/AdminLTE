<?php

namespace Database\Seeders;

use App\Models\ModuleHasModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleHasModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModuleHasModel::create([
            'user_id' => 1,
            'module_id' => 1
        ]);
    }
}
