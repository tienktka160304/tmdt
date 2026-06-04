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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('id_main_category')->unsigned();
            $table->string('name', 150); // Cột tên danh mục, kiểu VARCHAR, cho phép NULL
            $table->string('slug', 100)->unique();
            $table->string('image', 255)->nullable(); // Cột ảnh danh mục, kiểu VARCHAR, cho phép NULL
            $table->tinyInteger('status')->default(1)->comment('0 - hidden, 1 - active');
            $table->unsignedInteger('sort')->nullable(); // Cột thứ tự, cho phép NULL ??
            $table->foreign('id_main_category')->references('id')->on('main_categories')->onDelete('cascade');
            $table->timestamps(); // Tự động thêm created_at và updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
