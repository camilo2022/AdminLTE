<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Size::create(['name' => 'TALLA 04', 'code' => 'T04']);
        Size::create(['name' => 'TALLA 06', 'code' => 'T06']);
        Size::create(['name' => 'TALLA 08', 'code' => 'T08']);
        Size::create(['name' => 'TALLA 10', 'code' => 'T10']);
        Size::create(['name' => 'TALLA 12', 'code' => 'T12']);
        Size::create(['name' => 'TALLA 14', 'code' => 'T14']);
        Size::create(['name' => 'TALLA 16', 'code' => 'T16']);
        Size::create(['name' => 'TALLA 18', 'code' => 'T18']);
        Size::create(['name' => 'TALLA 20', 'code' => 'T20']);
        Size::create(['name' => 'TALLA 22', 'code' => 'T22']);
        Size::create(['name' => 'TALLA 24', 'code' => 'T24']);
        Size::create(['name' => 'TALLA 26', 'code' => 'T26']);
        Size::create(['name' => 'TALLA 28', 'code' => 'T28']);
        Size::create(['name' => 'TALLA 30', 'code' => 'T30']);
        Size::create(['name' => 'TALLA 32', 'code' => 'T32']);
        Size::create(['name' => 'TALLA 34', 'code' => 'T34']);
        Size::create(['name' => 'TALLA 35', 'code' => 'T35']);
        Size::create(['name' => 'TALLA 36', 'code' => 'T36']);
        Size::create(['name' => 'TALLA 37', 'code' => 'T37']);
        Size::create(['name' => 'TALLA 38', 'code' => 'T38']);
        Size::create(['name' => 'TALLA 39', 'code' => 'T39']);
        Size::create(['name' => 'TALLA 40', 'code' => 'T40']);
        Size::create(['name' => 'TALLA 41', 'code' => 'T41']);
        Size::create(['name' => 'TALLA UNICA', 'code' => 'TU']);
        Size::create(['name' => 'TALLA XXXS', 'code' => 'TXXXS']);
        Size::create(['name' => 'TALLA XXS', 'code' => 'TXXS']);
        Size::create(['name' => 'TALLA XS', 'code' => 'TXS']);
        Size::create(['name' => 'TALLA S', 'code' => 'TS']);
        Size::create(['name' => 'TALLA M', 'code' => 'TM']);
        Size::create(['name' => 'TALLA L', 'code' => 'TL']);
        Size::create(['name' => 'TALLA XL', 'code' => 'TXL']);
        Size::create(['name' => 'TALLA XXL', 'code' => 'TXXL']);
        Size::create(['name' => 'TALLA XXXL', 'code' => 'TXXXL']);
    }
}
