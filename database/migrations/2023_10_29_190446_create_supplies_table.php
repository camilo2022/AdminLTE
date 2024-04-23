<?php

use App\Models\ClothComposition;
use App\Models\ClothType;
use App\Models\Color;
use App\Models\MeasurementUnit;
use App\Models\Supplier;
use App\Models\SupplyType;
use App\Models\Trademark;
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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            /* $table->unsignedBigInteger('supplier_id')->comment('Identificador del proveedor.');
            $table->unsignedBigInteger('supply_type_id')->comment('Identificador del tipo de insumo.');
            $table->unsignedBigInteger('cloth_type_id')->comment('Identificador del tipo de tela.');
            $table->unsignedBigInteger('cloth_composition_id')->comment('Identificador de la composicion de la tela.'); */
            $table->foreignIdFor(Supplier::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(SupplyType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ClothType::class)->constrained()->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ClothComposition::class)->constrained()->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->string('description')->nullable();
            $table->float('quantity', 10, 2)->default(0);
            $table->enum('quality', ['N/A', 'BAJO', 'MEDIO', 'ALTO'])->default('N/A');
            $table->float('width', 10, 2)->default(0);
            $table->float('length', 10, 2)->default(0);            
            /* $table->unsignedBigInteger('measurement_unit_id')->comment('Identificador de la unidad de medida.');
            $table->unsignedBigInteger('color_id')->comment('Identificador de la unidad de medida.');
            $table->unsignedBigInteger('trademark_id')->comment('Identificador de la unidad de medida.'); */
            $table->foreignIdFor(MeasurementUnit::class)->constrained()->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Color::class)->constrained()->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Trademark::class)->constrained()->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->float('price_with_vat', 8, 2)->default(0);
            $table->float('price_without_vat', 8, 2)->default(0);            
            /* $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supply_type_id')->references('id')->on('supply_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cloth_type_id')->references('id')->on('cloth_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cloth_composition_id')->references('id')->on('cloth_compositions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('trademark_id')->references('id')->on('trademarks')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('supplies');
    }
};
