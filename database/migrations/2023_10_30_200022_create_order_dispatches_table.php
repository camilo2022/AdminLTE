<?php

use App\Models\Order;
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
        Schema::create('order_dispatches', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_id'); */
            $table->foreignIdFor(Order::class)->constrained();
            $table->unsignedBigInteger('dispatch_user_id');
            $table->enum('dispatch_status', ['Pendiente', 'Rechazado', 'Cancelado', 'Aprobado', 'Empacado', 'Despachado'])->default('Pendiente');
            $table->datetime('dispatch_date');
            $table->string('consecutive')->unique();
            $table->enum('payment_status', ['Pendiente de Pago', 'Pagado', 'Parcialmente Pagado', 'Cancelado', 'Reembolsado'])->default('Pendiente de Pago');
            /* $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade'); */
            $table->foreign('dispatch_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_dispatches');
    }
};
