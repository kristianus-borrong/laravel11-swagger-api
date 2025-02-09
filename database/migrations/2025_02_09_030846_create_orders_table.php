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
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id_orders', true)->nullable(false);
            $table->integer('id_customer')->nullable(false);
            $table->foreign('id_customer')->references('id_customer')->on('customers');
            $table->date('date')->nullable(false);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->nullable(false)->default('pending');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
