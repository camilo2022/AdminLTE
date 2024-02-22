<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesAndSubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['clothing_line_id' => 1, 'name' => 'JEANS DAMA', 'code' => '01', 'description' => 'JEANS DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'SHORT DAMA', 'code' => '02', 'description' => 'SHORT DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'FALDA DAMA', 'code' => '03', 'description' => 'FALDA DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'CAMISA DAMA', 'code' => '04', 'description' => 'CAMISA DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'TACON DAMA', 'code' => '05', 'description' => 'TACON DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'PLATAFORMA DAMA', 'code' => '06', 'description' => 'PLATAFORMA DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'DEPORTIVO DAMA', 'code' => '07', 'description' => 'DEPROTIVO DAMA']);
        Category::create(['clothing_line_id' => 1, 'name' => 'ESPADRILA DAMA', 'code' => '08', 'description' => 'ESPADRILA DAMA']);
        Category::create(['clothing_line_id' => 2, 'name' => 'JEANS CABALLERO', 'code' => '09', 'description' => 'JEANS CABALLERO']);
        Category::create(['clothing_line_id' => 2, 'name' => 'SHORT CABALLERO', 'code' => '10', 'description' => 'SHORT CABALLERO']);
        Category::create(['clothing_line_id' => 3, 'name' => 'JEANS NIÑO', 'code' => '12', 'description' => 'JEANS NIÑO']);
        Category::create(['clothing_line_id' => 4, 'name' => 'JEANS NIÑA', 'code' => '11', 'description' => 'JEANS NIÑA']);

        Subcategory::create(['category_id' => 1, 'name' => 'CON ROTOS DAMA', 'code' => '01', 'description' => 'JEANS CON ROTOS DAMA']);
        Subcategory::create(['category_id' => 9, 'name' => 'CON ROTOS CABALLERO', 'code' => '02', 'description' => 'JEANS CON ROTOS CABALLERO']);
        Subcategory::create(['category_id' => 11, 'name' => 'CON ROTOS NIÑO', 'code' => '03', 'description' => 'JEANS CON ROTOS NIÑO']);
        Subcategory::create(['category_id' => 12, 'name' => 'CON ROTOS NIÑA', 'code' => '04', 'description' => 'JEANS CON ROTOS NIÑA']);
        Subcategory::create(['category_id' => 2, 'name' => 'CLASICO DAMA', 'code' => '05', 'description' => 'SHORT CLASICO DAMA']);
        Subcategory::create(['category_id' => 10, 'name' => 'CLASICO CABALLERO', 'code' => '06', 'description' => 'SHORT CLASICO CABALLERO']);
        Subcategory::create(['category_id' => 3, 'name' => 'LARGA DAMA', 'code' => '07', 'description' => 'FALDA LARGA DAMA']);
        Subcategory::create(['category_id' => 4, 'name' => 'CORTA DAMA', 'code' => '08', 'description' => 'CAMISA CORTA DAMA']);
        Subcategory::create(['category_id' => 5, 'name' => 'ALTO DAMA', 'code' => '09', 'description' => 'TACON ALTO DAMA']);
        Subcategory::create(['category_id' => 6, 'name' => 'SEMI ALTO DAMA', 'code' => '10', 'description' => 'PLATAFORMA SEMI ALTO DAMA']);
        Subcategory::create(['category_id' => 7, 'name' => 'BAJO DAMA', 'code' => '11', 'description' => 'DEPORTIVO BAJO DAMA']);
        Subcategory::create(['category_id' => 8, 'name' => 'CON APERTURA FRONTAL DAMA', 'code' => '12', 'description' => 'ESPADRILA CON APERTURA FRONTAL DAMA']);

    }
}
