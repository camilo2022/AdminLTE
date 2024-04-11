<?php

use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tone;
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
            /* $table->unsignedBigInteger('order_id')->comment('Identificador del pedido.');
            $table->unsignedBigInteger('product_id')->comment('Identificador del producto.');
            $table->unsignedBigInteger('color_id')->comment('Identificador del color.');
            $table->unsignedBigInteger('tone_id')->comment('Identificador del tono.'); */
            $table->foreignIdFor(Order::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Product::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Color::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Tone::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('price', 8, 2)->comment('Valor de venta del producto.');
            $table->datetime('seller_date')->comment('Fecha de vendedor');
            $table->string('seller_observation')->nullable()->comment('Observacion de vendedor');
            $table->unsignedBigInteger('wallet_user_id')->nullable()->comment('Identificador del usuario de cartera.');
            $table->datetime('wallet_date')->nullable()->comment('Fecha de cartera.');
            $table->unsignedBigInteger('dispatched_user_id')->nullable()->comment('Identificador del usuario de despacho.');
            $table->datetime('dispatched_date')->nullable()->comment('Fecha de despacho');
            $table->enum('status', ['Pendiente', 'Cancelado', 'Revision', 'Aprobado', 'Agotado', 'Rechazado', 'Filtrado', 'Empacado', 'Despachado', 'Parcialmente Devuelto', 'Devuelto'])->default('Pendiente')->comment('Estado del detalle del pedido.');
            /* $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tone_id')->references('id')->on('tones')->onUpdate('cascade')->onDelete('cascade'); */
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
