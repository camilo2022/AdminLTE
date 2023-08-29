<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rol')->comment('Id del rol al que pertenece');
            $table->foreign('id_rol')->references('id')->on('roles');
            $table->unsignedBigInteger('id_module')->comment('Id del modulo al que pertence');
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
        Schema::dropIfExists('rol_modules');
    }
}
