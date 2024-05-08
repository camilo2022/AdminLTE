<?php

use App\Models\Product;
use App\Models\User;
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
        Schema::create('technical_datasheets', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('product_id')->comment('Identificador del product.');
            $table->unsignedBigInteger('user_id')->comment('Identificador del usuario.'); */
            $table->foreignIdFor(Product::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(User::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('technical_datasheet_status', ['En curso', 'Cancelado', 'Finalizado'])->default('En curso');
            $table->datetime('technical_datasheet_date')->nullable()->comment('Fecha de cartera');
            $table->string('technical_datasheet_observation')->nullable()->comment('Observacion de cartera');
            $table->json('cutting_process')/* ->default([
                'stroke_length' => (object) [
                    'value' => null,
                    'measurement_unit_id' => null
                ],
                'laying_meters' => (object) [
                    'value' => null,
                    'measurement_unit_id' => null,
                    'pocket_lining' => (object) [
                        'value' => null,
                        'measurement_unit_id' => null
                    ]
                ],
                'fabric_scraps' => (object) [
                    'value' => null,
                    'measurement_unit_id' => null
                ],
                'defective_pieces' => (object) [
                    'value' => null,
                    'measurement_unit_id' => null
                ],
                'average' => [
                    'real' => (object) [
                        'value' => null,
                        'measurement_unit_id' => null
                    ],
                    'stroke' => (object) [
                        'value' => null,
                        'measurement_unit_id' => null
                    ],
                ],
                'cutting_curve' => [

                ],
                'cloth_ids' => [

                ],
                'cutting_cost' => null
            ]) */;
            $table->json('special_service_process');
            $table->json('laundry_process');
            $table->json('decoration_process');
            $table->json('termination_process');
            /* $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('technical_datasheets');
    }
};
