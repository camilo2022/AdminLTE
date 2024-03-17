<?php

use App\Models\Color;
use App\Models\Product;
use App\Models\Tone;
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
        Schema::create('product_color_tone', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Color::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Tone::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('product_id')->comment('Identificador del prodcuto.');
            $table->unsignedBigInteger('color_id')->comment('Identificador del color.');
            $table->unsignedBigInteger('tone_id')->comment('Identificador del tono.');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tone_id')->references('id')->on('tones')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('product_color_tone');
    }
};
