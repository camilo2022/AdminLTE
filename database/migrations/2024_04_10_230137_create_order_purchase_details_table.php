<?php

use App\Models\Color;
use App\Models\OrderPurchase;
use App\Models\Product;
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
        Schema::create('order_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderPurchase::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Warehouse::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Product::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Color::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Tone::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('price', 8, 2);
            $table->datetime('date');
            $table->string('observation')->nullable();
            $table->enum('status', ['Pendiente', 'Cancelado', 'Aprobado', 'Parcialmente Recibido', 'Recibido'])->default('Pendiente');
            $table->index(['order_purchase_id', 'warehouse_id', 'product_id', 'color_id', 'tone_id'])->unique();
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
        Schema::dropIfExists('order_purchase_details');
    }
};
