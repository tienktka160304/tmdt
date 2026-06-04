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
        Schema::create('relate_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_product_main');
            $table->unsignedBigInteger('id_product_sub');

            $table->foreign('id_product_main')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('id_product_sub')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relate_products');
    }
};
