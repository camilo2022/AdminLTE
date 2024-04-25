<?php

use App\Models\Bank;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('account')->unique();
            $table->foreignIdFor(Bank::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('bank_id')->nullable(); */
            $table->unique(['model_id', 'model_type', 'account', 'bank_id'])->unique();
            /* $table->foreign('bank_id')->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('accounts');
    }
};
