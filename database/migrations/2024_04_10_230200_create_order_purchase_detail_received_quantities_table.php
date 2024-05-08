<?php

use App\Models\OrderPurchaseDetailRequestQuantity;
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
        Schema::create('order_purchase_detail_received_quantities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_purchase_detail_request_quantity_id');
            /* $table->foreignIdFor(OrderPurchaseDetailRequestQuantity::class)->constrained()->onUpdate('cascade')->onDelete('cascade'); */
            $table->unsignedBigInteger('quantity')->default(0);
            $table->foreign('order_purchase_detail_request_quantity_id', 'order_purchase_detail_rec_quantities_pur_detail_req_id_fk')->references('id')->on('order_return_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_purchase_detail_received_quantities');
    }
};
