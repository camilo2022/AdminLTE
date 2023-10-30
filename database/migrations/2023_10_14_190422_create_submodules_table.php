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
        Schema::create('submodules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the navigation submodule');
            $table->string('type')->default('subitem');
            $table->string('url')->comment('Route of the navigation submodule');
            $table->string('icon')->comment('Representative navigation icon for the submodule');
            $table->unsignedBigInteger('module_id')->comment('Identifier of the module');
            $table->unsignedBigInteger('permission_id')->comment('Identifier of the permission');
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('submodules');
    }
};
