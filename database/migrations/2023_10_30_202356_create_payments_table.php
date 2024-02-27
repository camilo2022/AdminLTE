<?php

use App\Models\Bank;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->unsignedBigInteger('value', 8, 2);
            $table->string('reference');
            $table->dateTime('date');
            /* $table->unsignedBigInteger('payment_type_id')->nullable(); */
            $table->foreignIdFor(PaymentType::class)->constrained();
            /* $table->unsignedBigInteger('bank_id')->nullable(); */
            $table->foreignIdFor(Bank::class)->nullable()->constrained();
            /* $table->foreign('bank_id')->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade'); 
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onUpdate('cascade')->onDelete('cascade'); */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
