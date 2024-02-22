<?php

namespace Database\Seeders;

use App\Models\SaleChannel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SaleChannel::create(['name' => 'VENTA NACIONAL', 'require_verify_wallet' => true]);
        SaleChannel::create(['name' => 'VENTA ASISTIDA', 'require_verify_wallet' => false]);
    }
}
