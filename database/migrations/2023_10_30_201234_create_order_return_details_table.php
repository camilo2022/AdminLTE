<?php

use App\Models\OrderDetail;
use App\Models\OrderReturn;
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
        Schema::create('order_return_details', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_return_id');
            $table->unsignedBigInteger('order_detail_id'); */
            $table->foreignIdFor(OrderReturn::class)->constrained();
            $table->foreignIdFor(OrderDetail::class)->constrained();
            /* $table->foreign('order_return_id')->references('id')->on('order_returns')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_return_details');
    }
};
