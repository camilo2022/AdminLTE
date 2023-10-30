<?php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('client_branch_id');
            $table->float('value', 8, 2);
            $table->string('dispatch');
            $table->date('dispatch_date');
            $table->unsignedBigInteger('seller_user_id');
            $table->string('seller_status');
            $table->date('seller_date');
            $table->string('seller_observation');
            $table->unsignedBigInteger('wallet_user_id');
            $table->string('wallet_status');
            $table->date('wallet_date');
            $table->string('wallet_observation');
            $table->string('dispatched_status');
            $table->date('dispatched_date');
            $table->string('payment_status');
            $table->unsignedBigInteger('collection_id');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('client_branch_id')->references('id')->on('client_branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('seller_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('wallet_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('collection_id')->references('id')->on('collections')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
};
