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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id(); 
            $table->string('consecutive')->unique();
            $table->unsignedBigInteger('send_warehouse_id');
            $table->unsignedBigInteger('send_user_id');
            $table->date('send_date');
            $table->time('send_time');
            $table->unsignedBigInteger('receive_warehouse_id');
            $table->unsignedBigInteger('receive_user_id');
            $table->date('receive_date')->nullable();
            $table->time('receive_time')->nullable();
            $table->string('transfer_status');
            $table->foreign('send_warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('send_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receive_warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receive_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('transfers');
    }
};
