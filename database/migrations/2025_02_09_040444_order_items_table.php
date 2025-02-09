<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->integer('id_order_item', true)->nullable(false);
            $table->integer('id_order')->nullable(false);
            $table->foreign('id_order')->references('id_orders')->on('orders');
            $table->integer('id_product')->nullable(false);
            $table->foreign('id_product')->references('id_product')->on('products');
            $table->integer('quantity')->length(4)->nullable(false)->default(0);
            $table->bigInteger('price')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
