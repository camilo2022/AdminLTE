<?php

use App\Models\Order;
use App\Models\ReturnType;
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
        Schema::create('order_payment_types', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('return_type_id'); */
            $table->foreignIdFor(Order::class)->constrained();
            $table->foreignIdFor(ReturnType::class)->constrained();
            /* $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('return_type_id')->references('id')->on('return_types')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('order_payment_types');
    }
};
