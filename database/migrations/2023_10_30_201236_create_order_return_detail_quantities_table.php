<?php

use App\Models\OrderDetailQuantity;
use App\Models\OrderReturnDetail;
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
        Schema::create('order_return_detail_quantities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_return_detail_id');
            $table->unsignedBigInteger('order_detail_quantity_id');
            /* $table->foreignIdFor(OrderReturnDetail::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->name('order_return_detail_quantities_return_detail_id_fk');
            $table->foreignIdFor(OrderDetailQuantity::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->name('order_return_detail_quantities_detail_quantity_id_fk'); */
            $table->unsignedBigInteger('quantity')->default(0);
            /* $table->index(['order_return_detail_id', 'order_detail_quantity_id'])->unique(); */
            $table->foreign('order_return_detail_id', 'order_return_detail_quantities_return_detail_id_fk')->references('id')->on('order_return_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_detail_quantity_id', 'order_return_detail_quantities_detail_quantity_id_fk')->references('id')->on('order_detail_quantities')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_return_detail_quantities');
    }
};
