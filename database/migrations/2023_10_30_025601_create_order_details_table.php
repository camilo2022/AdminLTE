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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id()->comment('Identificador del detalle del pedido.');
            $table->unsignedBigInteger('order_id')->comment('Identificador del pedido.');
            $table->unsignedBigInteger('product_id')->comment('Identificador del producto.');
            $table->unsignedBigInteger('color_id')->comment('Identificador del color.');
            $table->float('price', 8, 2)->comment('Valor de venta del producto.');
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
            $table->datetime('seller_date')->comment('Fecha de vendedor');
            $table->string('seller_observation')->comment('Observacion de vendedor');
            $table->unsignedBigInteger('wallet_user_id')->nullable()->comment('Identificador del usuario de cartera.');
            $table->datetime('wallet_date')->nullable()->comment('Fecha de cartera.');
            $table->unsignedBigInteger('dispatched_user_id')->nullable()->comment('Identificador del usuario de despacho.');
            $table->datetime('dispatched_date')->nullable()->comment('Fecha de despacho');
            $table->string('order_detail_status')->comment('Estado del detalle del pedido.');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('wallet_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dispatched_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_details');
    }
};
