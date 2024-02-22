<?php

use App\Models\OrderPacking;
use App\Models\PackageType;
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
        Schema::create('order_packages', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_packing_id');
            $table->unsignedBigInteger('package_type_id'); */
            $table->foreignIdFor(OrderPacking::class)->constrained();
            $table->foreignIdFor(PackageType::class)->constrained();
            $table->string('weight');
            $table->string('package_status');
            $table->datetime('package_date');
            /* $table->foreign('order_packing_id')->references('id')->on('order_packings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('package_type_id')->references('id')->on('package_types')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('order_packages');
    }
};
