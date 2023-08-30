<?php

namespace Database\Seeders;

use App\Models\UserEnterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserEnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserEnterprise::create([
            'user_id' => 1,
            'enterprises_id' => 1,
        ]);

        UserEnterprise::create([
            'user_id' => 2,
            'enterprises_id' => 1,
        ]);
    }
}
