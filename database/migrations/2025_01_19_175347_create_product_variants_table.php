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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_product');
            $table->string('option', 150); // Cột tên danh mục, kiểu VARCHAR, cho phép NULL
            $table->decimal('price', 12, 2); // gia (FLOAT, NOT NULL)
            $table->string('image', 255)->nullable(); // anh_san_pham (VARCHAR 255, NULL)
            $table->unsignedInteger('stock')->default(0); // ton_kho (INT, NOT NULL)// Phạm vi: 0 đến 65,535

            $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
