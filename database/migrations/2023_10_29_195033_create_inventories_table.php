<?php

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Tone;
use App\Models\Warehouse;
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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Size::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Warehouse::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Color::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Tone::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('size_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('tone_id'); */
            $table->unsignedBigInteger('quantity')->default(0);
            /* $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tone_id')->references('id')->on('tones')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('inventories');
    }
};
