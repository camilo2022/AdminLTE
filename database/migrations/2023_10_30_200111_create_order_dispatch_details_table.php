<?php

use App\Models\OrderDetail;
use App\Models\OrderDispatch;
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
            $table->id()->comment('Identificador del detalle de la orden de despacho.');
            /* $table->unsignedBigInteger('order_dispatch_id')->comment('Idetificador de la orden de despacho.');
            $table->unsignedBigInteger('order_detail_id')->comment('Identificador del detalle de la orden.'); */
            $table->foreignIdFor(OrderDispatch::class)->constrained();
            $table->foreignIdFor(OrderDetail::class)->constrained();
            $table->enum('detail_status', ['Pendiente', 'Rechazado', 'Cancelado', 'Aprobado', 'Empacado', 'Despachado'])->default('Pendiente')->comment('Estado del detalle de la orden de despacho.');
            /* $table->foreign('order_dispatch_id')->references('id')->on('order_dispatches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_detail_id')->references('id')->on('order_details')->onUpdate('cascade')->onDelete('cascade'); */
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
