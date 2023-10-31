<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('collections', function (Blueprint $table) {
            $table->id()->comment('Identificador de la correria.');
            $table->string('name')->comment('Nombre de la correria.');
            $table->string('code')->unique()->comment('Codigo de la correria.');
            $table->datetime('start_date')->comment('Fecha de inicio de la correria.');
            $table->datetime('end_date')->comment('Fecha de fin de la correria.');
            $table->boolean('active_status')->comment('Estado de la correria.');
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
        Schema::dropIfExists('collections');
    }
};
