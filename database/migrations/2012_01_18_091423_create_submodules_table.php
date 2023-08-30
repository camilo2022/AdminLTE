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
            $table->string('name_submodules')->comment('Nombre de los submodulos');
            $table->string('route')->comment('Nombre de los submodulos')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('id_module')->comment('Identificacion de los modulos')->nullable();
            $table->foreign('id_module')->references('id')->on('modules');
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
