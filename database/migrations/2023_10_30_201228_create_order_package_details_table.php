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
        Schema::create('order_package_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_package_id');
            $table->unsignedBigInteger('order_dispatch_detail_id');
            $table->foreign('order_package_id')->references('id')->on('order_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_dispatch_detail_id')->references('id')->on('order_dispatch_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_package_details');
    }
};
