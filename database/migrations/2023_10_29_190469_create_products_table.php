<?php

use App\Models\Category;
use App\Models\ClothingLine;
use App\Models\Correria;
use App\Models\Model;
use App\Models\Subcategory;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->float('price', 8, 2);
            $table->float('cost', 8, 2);
            $table->foreignIdFor(ClothingLine::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Category::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Subcategory::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Model::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Trademark::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Correria::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            /* $table->unsignedBigInteger('clothing_line_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('trademark_id');
            $table->unsignedBigInteger('correria_id');
            $table->foreign('clothing_line_id')->references('id')->on('clothing_lines')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('models')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('trademark_id')->references('id')->on('trademarks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('correria_id')->references('id')->on('correrias')->onUpdate('cascade')->onDelete('cascade'); */
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
        Schema::dropIfExists('products');
    }
};
