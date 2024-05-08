<?php

use App\Models\OrderPurchaseDetail;
use App\Models\Size;
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
        Schema::create('order_purchase_detail_request_quantities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_purchase_detail_id');
            /* $table->foreignIdFor(OrderPurchaseDetail::class)->constrained()->onUpdate('cascade')->onDelete('cascade'); */
            $table->foreignIdFor(Size::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quantity')->default(0);
            /* $table->index(['order_purchase_detail_id', 'size_id'])->unique(); */
            $table->foreign('order_purchase_detail_id', 'order_purchase_detail_req_quantities_purchase_detail_id_fk')->references('id')->on('order_return_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_purchase_detail_request_quantities');
    }
};
