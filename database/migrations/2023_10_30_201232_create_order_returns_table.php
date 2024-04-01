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
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('order_id')->unique(); */
            $table->foreignIdFor(Order::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('return_user_id');
            /* $table->unsignedBigInteger('return_type_id'); */
            $table->foreignIdFor(ReturnType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('package_status', ['Pendiente', 'Aprobado'])->default('Pendiente');
            $table->datetime('return_date');
            /* $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade'); */
            $table->foreign('return_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            /* $table->foreign('return_type_id')->references('id')->on('return_types')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('order_returns');
    }
};
