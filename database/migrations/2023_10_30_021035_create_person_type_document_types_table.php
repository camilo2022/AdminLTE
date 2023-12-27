<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_type_document_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_type_id')->comment('Identificador del tipo de persona.');
            $table->unsignedBigInteger('document_type_id')->comment('Identificador del tipo de documento.');
            $table->foreign('person_type_id')->references('id')->on('person_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_type_document_types');
    }
};
