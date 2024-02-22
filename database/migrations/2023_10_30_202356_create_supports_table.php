<?php

use App\Models\Bank;
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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->float('value', 8, 2);
            $table->string('reference');
            $table->dateTime('date');
            $table->enum('type', ['Soporte de Pago', 'Soporte de Factura']);
            /* $table->unsignedBigInteger('bank_id')->nullable(); */
            $table->foreignIdFor(Bank::class)->constrained()->nullable();
            /* $table->foreign('bank_id')->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade'); */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supports');
    }
};
