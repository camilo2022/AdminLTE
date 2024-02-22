<?php

namespace Database\Seeders;

use App\Models\ClientType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientType::create(['name' => 'VENTA NACIONAL', 'code' => 'VN', 'require_quota' => true]);
        ClientType::create(['name' => 'VENTA ASISTIDA', 'code' => 'VA', 'require_quota' => false]);
        ClientType::create(['name' => 'VENTA EMPLEADOS', 'code' => 'VE', 'require_quota' => false]);
    }
}
