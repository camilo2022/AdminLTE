<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Identificador del usuario.');
            $table->string('name')->comment('Nombre del usuario.');
            $table->string('last_name')->comment('Apellido del usuario.');
            $table->string('document_number')->unique()->comment('Numero de documento del usuario.');
            $table->string('phone_number')->comment('Numero del telefono del usuario.');
            $table->string('address')->comment('Direccion del usuario.');
            $table->string('email')->unique()->comment('Correo del usuario.');
            $table->timestamp('email_verified_at')->nullable()->comment('Verificacion del correo del usuario.');
            $table->string('password')->comment('Contraseña del usuario.');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
