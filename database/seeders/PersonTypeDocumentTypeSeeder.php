<?php

namespace Database\Seeders;

use App\Models\PersonTypeDocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonTypeDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PersonTypeDocumentType::create(['person_type_id' => 1, 'document_type_id' => 1]);
        PersonTypeDocumentType::create(['person_type_id' => 1, 'document_type_id' => 2]);
        PersonTypeDocumentType::create(['person_type_id' => 2, 'document_type_id' => 3]);
        PersonTypeDocumentType::create(['person_type_id' => 1, 'document_type_id' => 4]);
        PersonTypeDocumentType::create(['person_type_id' => 1, 'document_type_id' => 5]);
        PersonTypeDocumentType::create(['person_type_id' => 1, 'document_type_id' => 6]);
    }
}
