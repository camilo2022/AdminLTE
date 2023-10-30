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
            $table->unsignedBigInteger('quantity');
            $table->string('packing_status');
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
