<?php

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
        Schema::create('order_packings', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_dispatch_id')->unique(); */
            $table->foreignIdFor(OrderDispatch::class)->constrained();
            $table->unsignedBigInteger('packing_user_id');
            $table->enum('packing_status', ['Empacando', 'Finalizado'])->default('Empacando');
            $table->datetime('packing_date');
/*             $table->foreign('order_dispatch_id')->references('id')->on('order_dispatches')->onUpdate('cascade')->onDelete('cascade'); */
            $table->foreign('packing_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_packings');
    }
};
