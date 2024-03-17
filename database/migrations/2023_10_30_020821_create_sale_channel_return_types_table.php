<?php

use App\Models\ReturnType;
use App\Models\SaleChannel;
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
        Schema::create('sale_channel_return_types', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SaleChannel::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ReturnType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('sale_channel_id')->comment('Identificador de la bodega.');
            $table->unsignedBigInteger('return_type_id')->comment('Identificador del usuario.');
            $table->foreign('sale_channel_id')->references('id')->on('sale_channels')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sale_channel_return_types');
    }
};
