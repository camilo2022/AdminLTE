<?php

use App\Models\OrderDispatchDetailQuantity;
use App\Models\OrderPackageDetail;
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
        Schema::create('order_package_detail_quantities', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_package_detail_id');
            $table->unsignedBigInteger('order_dispatch_detail_quantity_id')->comment('Identificador de la cantidad del detalle de la orden de despacho.'); */
            $table->foreignIdFor(OrderPackageDetail::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->name('order_package_detail_quantities_package_detail_id_fk');
            $table->foreignIdFor(OrderDispatchDetailQuantity::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->name('order_package_detail_quantities_dispatch_detail_quantity_id_fk');
            $table->unsignedBigInteger('quantity')->default(0);
            /* $table->foreign('order_package_detail_id', 'order_package_detail_quantities_package_detail_id_fk')->references('id')->on('order_package_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_dispatch_detail_quantity_id', 'order_package_detail_quantities_dispatch_detail_quantity_id_fk')->references('id')->on('order_dispatch_detail_quantities')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('order_package_detail_quantities');
    }
};
