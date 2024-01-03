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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->comment('Identificador del pedido.');
            $table->unsignedBigInteger('client_id')->comment('Identificador del cliente.');
            $table->unsignedBigInteger('client_branch_id')->comment('Identificador de la sucursal del cliente.');
            $table->enum('dispatch', ['De inmediato', 'Antes de', 'Despues de'])->comment('Cuando despachar.');
            $table->date('dispatch_date')->nullable()->comment('Fecha de cuando despachar.');
            $table->unsignedBigInteger('seller_user_id')->comment('Identificador del usuario de vendedor.');
            $table->enum('seller_status', ['Pendiente', 'Cancelado', 'Aprobado'])->default('Pendiente')->comment('Estado del vendedor.');
            $table->datetime('seller_date')->comment('Fecha del vendedor.');
            $table->string('seller_observation')->nullable()->comment('Observacion del vendedor.');
            $table->unsignedBigInteger('wallet_user_id')->nullable()->comment('Identificador del usuario de cartera.');
            $table->enum('wallet_status', ['Pendiente', 'Cancelado', 'Aprobado'])->default('Pendiente')->comment('Estado de cartera.');
            $table->datetime('wallet_date')->nullable()->comment('Fecha de cartera');
            $table->string('wallet_observation')->nullable()->comment('Observacion de cartera');
            $table->enum('dispatched_status', ['Pendiente', 'Cancelado', 'Parcialmente Aprobado', 'Aprobado', 'Devuelto', 'Parcialmente Despachado', 'Despachado'])->default('Pendiente')->comment('Estado de despacho.');
            $table->datetime('dispatched_date')->nullable()->comment('Fecha de despacho.');
            $table->unsignedBigInteger('correria_id')->comment('Identificador de la correria.');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('client_branch_id')->references('id')->on('client_branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('seller_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('wallet_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('correria_id')->references('id')->on('correrias')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
};
