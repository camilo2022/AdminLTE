<?php

namespace Database\Seeders;

use App\Models\SubModuleEnterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubModuleEnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubModuleEnterprise::create([ 'submodules_id' => 1, 'enterprises_id' => 1, ]);
        SubModuleEnterprise::create([ 'submodules_id' => 2, 'enterprises_id' => 1, ]);
        SubModuleEnterprise::create([ 'submodules_id' => 3, 'enterprises_id' => 1, ]);
        SubModuleEnterprise::create([ 'submodules_id' => 4, 'enterprises_id' => 1, ]);
        SubModuleEnterprise::create([ 'submodules_id' => 5, 'enterprises_id' => 1, ]);
        SubModuleEnterprise::create([ 'submodules_id' => 6, 'enterprises_id' => 1, ]);
        SubModuleEnterprise::create([ 'submodules_id' => 7, 'enterprises_id' => 1, ]);
    }
}
