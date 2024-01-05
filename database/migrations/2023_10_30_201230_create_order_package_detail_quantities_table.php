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
        Schema::create('order_package_detail_quantities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_dispatch_detail_quantity_id')->comment('Identificador de la cantidad del detalle de la orden de despacho.');
            $table->unsignedBigInteger('quantity')->default(0);
            $table->foreign('order_dispatch_detail_quantity_id', 'order_package_detail_quantities_dispatch_detail_quantity_id_fk')->references('id')->on('order_dispatch_detail_quantities')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_package_detail_quantities');
    }
};
