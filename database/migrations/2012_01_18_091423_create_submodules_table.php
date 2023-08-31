<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmodulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submodules', function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->comment('Nombre de los submodulos');
            $table->string('route')->comment('Nombre de los submodulos');
            $table->unsignedBigInteger('module_id')->comment('Identificacion de los modulos');
            $table->unsignedBigInteger('role_id')->comment('Identificacion del rol');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('role_id')->references('id')->on('roles');
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
}
