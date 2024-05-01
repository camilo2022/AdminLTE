<?php

use App\Models\Workshop;
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
        Schema::create('order_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Workshop::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_user_id');
            $table->enum('purchase_status', ['Pendiente', 'Cancelado', 'Aprobado', 'Parcialmente Recibido', 'Recibido'])->default('Pendiente');
            $table->datetime('purchase_date');
            $table->string('purchase_observation')->nullable();
            $table->enum('payment_status', ['Pendiente de Pago', 'Parcialmente Pagado', 'Pagado', 'Cancelado'])->default('Pendiente de Pago');
            $table->datetime('payment_date')->nullable();
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
        Schema::dropIfExists('order_purchases');
    }
};
