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
        Schema::create('submodules', function (Blueprint $table) {
            $table->id()->comment('Identificador del submodulo.');
            $table->string('name')->comment('Nombre del submodulo.');
            $table->string('type')->default('subitem')->comment('Tipo de registro');
            $table->string('url')->comment('Url de navegacion del submodulo.');
            $table->string('icon')->comment('Icono del submodulo.');
            $table->unsignedBigInteger('module_id')->comment('Identificador del modulo.');
            $table->unsignedBigInteger('permission_id')->comment('Identificador del permiso.');
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('submodules');
    }
};
