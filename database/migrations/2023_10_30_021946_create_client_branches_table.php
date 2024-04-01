<?php

use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Departament;
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
        Schema::create('client_branches', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('client_id'); */
            $table->foreignIdFor(Client::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            /* $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('departament_id');
            $table->unsignedBigInteger('city_id'); */
            $table->foreignIdFor(Country::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Departament::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(City::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('address');
            $table->string('neighborhood');
            $table->string('description')->nullable();
            $table->string('email');
            $table->string('telephone_number_first');
            $table->string('telephone_number_second')->nullable();
            $table->index(['client_id', 'code'])->unique();
            /* $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('departament_id')->references('id')->on('departaments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('client_branches');
    }
};
