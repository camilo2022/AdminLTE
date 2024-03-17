<?php

use App\Models\Correria;
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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Correria::class)->constrained()->unique()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('correria_id')->unique(); */
            $table->date('date_definition_start_pilots');
            $table->date('date_definition_start_samples');
            $table->decimal('proyection_stop_warehouse', 5, 2)->default(0);
            $table->unsignedBigInteger('number_samples_include_suitcase')->default(0);
            /* $table->foreign('correria_id')->references('id')->on('correrias')->onUpdate('cascade')->onDelete('cascade'); */
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
