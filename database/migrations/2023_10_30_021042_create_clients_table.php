<?php

use App\Models\City;
use App\Models\ClientType;
use App\Models\Country;
use App\Models\Departament;
use App\Models\DocumentType;
use App\Models\PersonType;
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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(PersonType::class)->constrained();
            $table->foreignIdFor(ClientType::class)->constrained();
            $table->foreignIdFor(DocumentType::class)->constrained();
            /* $table->unsignedBigInteger('person_type_id');
            $table->unsignedBigInteger('client_type_id');
            $table->unsignedBigInteger('document_type_id'); */
            $table->string('document_number')->unique();
            $table->foreignIdFor(Country::class)->constrained();
            $table->foreignIdFor(Departament::class)->constrained();
            $table->foreignIdFor(City::class)->constrained();
            /* $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('departament_id');
            $table->unsignedBigInteger('city_id'); */
            $table->string('address');
            $table->string('neighborhood');
            $table->string('email');
            $table->string('telephone_number_first');
            $table->string('telephone_number_second')->nullable();
            $table->unsignedBigInteger('quota')->default(0);
            /* $table->foreign('person_type_id')->references('id')->on('person_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('client_type_id')->references('id')->on('client_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('clients');
    }
};
