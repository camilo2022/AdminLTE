<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id()->comment('Identificador de la ciudad.');
            $table->unsignedBigInteger('province_id')->comment('Identificador de la provincia.');
            $table->unsignedBigInteger('departament_id')->comment('Identificador del departamento.');
            $table->string('name')->comment('Nombre de la ciudad.');
            $table->string('code')->unique()->comment('Codigo de la ciudad.');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('departament_id')->references('id')->on('departaments')->onDelete('cascade');
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
        Schema::dropIfExists('cities');
    }
}
