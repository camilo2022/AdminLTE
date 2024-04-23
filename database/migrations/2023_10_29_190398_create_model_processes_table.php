<?php

use App\Models\Process;
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
        Schema::create('model_processes', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            /* $table->unsignedBigInteger('process_id'); */
            $table->foreignIdFor(Process::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['model_id', 'model_type', 'process_id']);
            /* $table->foreign('process_id')->references('id')->on('processes')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('model_processes');
    }
};
