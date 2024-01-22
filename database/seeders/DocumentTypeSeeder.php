<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentType::create(['name' => 'CEDULA DE CIUDADANIA', 'code' => 'CC']);
        DocumentType::create(['name' => 'CEDULA DE EXTRANJERIA', 'code' => 'CE']);
        DocumentType::create(['name' => 'NUMERO DE IDENTIFICACION TRIBUTARIA', 'code' => 'NIT']);
        DocumentType::create(['name' => 'PASAPORTE', 'code' => 'PA']);
        DocumentType::create(['name' => 'NUMERO UNICO DE IDENTIFICACION PERSONAL', 'code' => 'NUIP']);
        DocumentType::create(['name' => 'PERMISO POR PROTECCION TEMPORAL', 'code' => 'PPT']);
    }
}
