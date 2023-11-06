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
            $table->timestamps();
            $table->softDeletes();
        });
        
        DB::unprepared('DROP PROCEDURE IF EXISTS collections');

        DB::unprepared('
            CREATE PROCEDURE collections(IN currentDateTime DATETIME)
            BEGIN
                -- Actualizar los registros que no cumplen la condición
                UPDATE collections
                SET deleted_at = currentDateTime
                WHERE start_date > currentDateTime OR end_date < currentDateTime;

                -- Restaurar los registros que cumplen la condición
                UPDATE collections
                SET deleted_at = NULL
                WHERE start_date <= currentDateTime AND end_date >= currentDateTime;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
        DB::unprepared('DROP PROCEDURE IF EXISTS collections');
    }
};
