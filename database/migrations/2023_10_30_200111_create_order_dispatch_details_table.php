<?php

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
        Schema::create('order_dispatch_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_dispatch_id');
            $table->unsignedBigInteger('order_detail_id');
            $table->unsignedBigInteger('02')->default(0)->comment('Cantidad unidades en talla 02');
            $table->unsignedBigInteger('04')->default(0)->comment('Cantidad unidades en talla 04');
            $table->unsignedBigInteger('06')->default(0)->comment('Cantidad unidades en talla 06');
            $table->unsignedBigInteger('08')->default(0)->comment('Cantidad unidades en talla 08');
            $table->unsignedBigInteger('10')->default(0)->comment('Cantidad unidades en talla 10');
            $table->unsignedBigInteger('12')->default(0)->comment('Cantidad unidades en talla 12');
            $table->unsignedBigInteger('14')->default(0)->comment('Cantidad unidades en talla 14');
            $table->unsignedBigInteger('16')->default(0)->comment('Cantidad unidades en talla 16');
            $table->unsignedBigInteger('18')->default(0)->comment('Cantidad unidades en talla 18');
            $table->unsignedBigInteger('20')->default(0)->comment('Cantidad unidades en talla 20');
            $table->unsignedBigInteger('22')->default(0)->comment('Cantidad unidades en talla 22');
            $table->unsignedBigInteger('24')->default(0)->comment('Cantidad unidades en talla 24');
            $table->unsignedBigInteger('26')->default(0)->comment('Cantidad unidades en talla 26');
            $table->unsignedBigInteger('28')->default(0)->comment('Cantidad unidades en talla 28');
            $table->unsignedBigInteger('30')->default(0)->comment('Cantidad unidades en talla 30');
            $table->unsignedBigInteger('32')->default(0)->comment('Cantidad unidades en talla 32');
            $table->unsignedBigInteger('34')->default(0)->comment('Cantidad unidades en talla 34');
            $table->unsignedBigInteger('36')->default(0)->comment('Cantidad unidades en talla 36');
            $table->unsignedBigInteger('38')->default(0)->comment('Cantidad unidades en talla 38');
            $table->unsignedBigInteger('XXXS')->default(0)->comment('Cantidad unidades en talla XXXS');
            $table->unsignedBigInteger('XXS')->default(0)->comment('Cantidad unidades en talla XXS');
            $table->unsignedBigInteger('XS')->default(0)->comment('Cantidad unidades en talla XS');
            $table->unsignedBigInteger('S')->default(0)->comment('Cantidad unidades en talla S');
            $table->unsignedBigInteger('M')->default(0)->comment('Cantidad unidades en talla M');
            $table->unsignedBigInteger('L')->default(0)->comment('Cantidad unidades en talla L');
            $table->unsignedBigInteger('XL')->default(0)->comment('Cantidad unidades en talla XL');
            $table->unsignedBigInteger('XXL')->default(0)->comment('Cantidad unidades en talla XXL');
            $table->unsignedBigInteger('XXXL')->default(0)->comment('Cantidad unidades en talla XXXL');
            $table->string('order_dispatch_detail_status');
            $table->foreign('order_dispatch_id')->references('id')->on('order_dispatches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_detail_id')->references('id')->on('order_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_dispatch_details');
    }
};
