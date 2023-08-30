<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEnterprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_enterprises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Id del usuario');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('enterprises_id')->comment('Id de la empresa');
            $table->foreign('enterprises_id')->references('id')->on('enterprises');
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
        Schema::dropIfExists('user_enterprises');
    }
}
