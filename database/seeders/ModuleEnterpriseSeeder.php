<?php

namespace Database\Seeders;

use App\Models\ModuleEnterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleEnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModuleEnterprise::create([ 'modules_id' => 1, 'enterprises_id' => 1, ]);
    }
}
