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
        Schema::create('people_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('name');
            $table->string('last_name');
            $table->unsignedBigInteger('document_type_id');
            $table->string('document_number')->unique();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('departament_id');
            $table->unsignedBigInteger('city_id');
            $table->string('address');
            $table->string('neighborhood');
            $table->string('email');
            $table->string('telephone_number_first');
            $table->string('telephone_number_second')->nullable();
            $table->foreign('person_id')->references('id')->on('people')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_references');
    }
};
