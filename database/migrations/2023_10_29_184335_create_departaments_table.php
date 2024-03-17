<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departaments', function (Blueprint $table) {
            $table->id()->comment('Identificador del departamento.');
            $table->foreignIdFor(Country::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('country_id')->comment('Identificador del pais.'); */
            $table->string('name')->comment('Nombre del departamento.');
            /* $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade'); */
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
        Schema::dropIfExists('departaments');
    }
}
