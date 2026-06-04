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
        Schema::create('attribute_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_product'); // Foreign key column for products
            $table->string("key", 150);
            $table->string("value", 1000);
            $table->unsignedInteger('sort')->nullable(); // Cột thứ tự, cho phép NULL ??
            $table->foreign('id_product')->references('id')->on("products")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_products');
    }
};
