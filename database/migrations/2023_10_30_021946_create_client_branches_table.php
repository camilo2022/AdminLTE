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
        Schema::create('client_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->unsignedBigInteger('document_type_id');
            $table->string('document_number');
            $table->string('telephone_number');
            $table->string('email');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('departament_id');
            $table->unsignedBigInteger('city_id');
            $table->string('address');
            $table->string('neighborhood');
            $table->string('description')->nullable();
            $table->foreign('document_type_id')->references('id')->on('document_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('departament_id')->references('id')->on('departaments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('client_branches');
    }
};
