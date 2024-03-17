<?php

use App\Models\OrderDetail;
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
        Schema::create('order_detail_quantities', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_detail_id')->comment('Identificador del detalle de la orden.');
            $table->unsignedBigInteger('size_id')->comment('Identificador de la talla.'); */
            $table->foreignIdFor(OrderDetail::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Size::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quantity')->default(0);
            /* $table->foreign('order_detail_id')->references('id')->on('order_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('order_detail_quantities');
    }
};
