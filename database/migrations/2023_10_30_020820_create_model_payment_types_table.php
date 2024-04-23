<?php

use App\Models\Order;
use App\Models\PaymentType;
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
        Schema::create('model_payment_types', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            /* $table->unsignedBigInteger('payment_type_id'); */
            $table->foreignIdFor(PaymentType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['model_id', 'model_type', 'payment_type_id']);
            /* $table->foreign('payment_type_id')->references('id')->on('payment_types')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('model_payment_types');
    }
};
